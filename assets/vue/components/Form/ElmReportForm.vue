<template>

  <form-field for-id="observation" :label="$t('observation._name')" :field="fields.observation">
    <custom-select id="observation" :choices="observationChoices" :field="fields.observation"
                   v-model="entity.observation" @update:model-value="validateField('observation')"/>
  </form-field>

  <actionable-preview v-if="entity.observation">
    <identification-view v-if="entity.observation.analysisType === 'IDENTIFICATION'"
                         :organisms="organisms" :observation="entity.observation"/>
  </actionable-preview>

  <hr/>

  <p class="alert alert-warning" v-if="leadingCodeChoices.length === 0">
    {{ $t('_form.elm_report.no_leading_choices_for_pathogen') }}
  </p>
  <form-field v-else for-id="leadingCode" :label="$t('leading_code._name')" :field="fields.leadingCode">
    <custom-select id="leadingCode" :choices="leadingCodeChoices" :field="fields.leadingCode"
                   v-model="entity.leadingCode" @update:model-value="validateField('leadingCode')"/>
  </form-field>

  <template v-if="entity.leadingCode">
    <form-field for-id="organism" :label="$t('organism._name')" :field="fields.organism">
      <searchable-select v-if="entity.leadingCode?.interpretationGroup !== 'TEXT'"
                         id="organism" :choices="organismChoices" :field="fields.organism"
                         v-model="entity.organism" @update:model-value="validateField('organism')"/>
      <text-input v-else
                  id="organismText" :field="fields.organismText" v-model="entity.organismText"
                  @blur="blurField('organismText')" @update:modelValue="validateField('organismText')"/>
    </form-field>

    <form-field for-id="specimen" :label="$t('specimen._name')" :field="fields.specimen">
      <custom-select id="specimen" :choices="specimenChoices" :field="fields.specimen"
                     v-model="entity.specimen" @update:model-value="validateField('specimen')"/>
    </form-field>

    <form-field for-id="interpretation" :label="$t('observation.interpretation')" :field="fields.interpretation">
      <custom-select id="interpretation" :choices="interpretationChoices" :field="fields.interpretation"
                     v-model="entity.interpretation" @update:model-value="validateField('interpretation')"/>
    </form-field>
  </template>
</template>

<script>
import {templatedForm, createField, requiredRule} from './utils/form'
import FormField from '../Library/FormLayout/FormField'
import TextInput from '../Library/FormInput/TextInput.vue'
import TextArea from '../Library/FormInput/TextArea.vue'
import DateTimeInput from '../Library/FormInput/DateTimeInput.vue'
import Radio from "../Library/FormInput/Radio.vue";
import CustomSelect from "../Library/FormInput/CustomSelect.vue";
import Checkbox from "../Library/FormInput/Checkbox.vue";
import IdentificationView from "../View/Observation/IdentificationView.vue";
import ActionablePreview from "../Library/View/ActionablePreview.vue";
import {formatObservation} from "../../services/domain/formatter";
import SearchableSelect from "../Library/FormInput/SearchableSelect.vue";

export default {
  emits: ['update'],
  components: {
    SearchableSelect,
    ActionablePreview,
    IdentificationView,
    Checkbox,
    CustomSelect,
    Radio,
    DateTimeInput,
    TextArea,
    TextInput,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        observation: createField(requiredRule),
        leadingCode: createField(requiredRule),

        organism: createField(),
        organismText: createField(),
        specimen: createField(),
        interpretation: createField(),
      },
      entity: {
        observation: null,
        leadingCode: null,

        organism: null,
        organismText: null,
        specimen: null,
        interpretation: null,
      },
    }
  },
  props: {
    probe: {
      type: Object,
      required: false
    },
    observations: {
      type: Array,
      required: true
    },
    leadingCodes: {
      type: Array,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
    specimens: {
      type: Array,
      required: true
    },
  },
  computed: {
    observationChoices: function () {
      return this.observations.map(o => ({label: formatObservation(o, this.organisms, this.$t), value: o}))
    },
    leadingCodeChoices: function () {
      const filtered = this.leadingCodes.filter(lc => lc.pathogen === this.probe.pathogen)
      return filtered.map(organism => ({label: organism.displayName, value: organism}))
    },
    organismChoices: function () {
      let filtered = [...this.organisms]
      if (this.entity.leadingCode?.organismGroup) {
        filtered = filtered.filter(s => s.organismGroup === this.entity.leadingCode.organismGroup)
      }
      return filtered.map(organism => ({label: organism.displayName, value: organism}))
    },
    specimenChoices: function () {
      let filtered = [...this.specimens]
      if (this.entity.leadingCode?.specimen) {
        filtered = [filtered.find(s => s['@id'] === this.entity.leadingCode.specimen)].filter(s => s)
      } else if (this.entity.leadingCode?.specimenGroup) {
        filtered = filtered.filter(s => s.specimenGroup === this.entity.leadingCode.specimenGroup)
      }

      return filtered.map(specimen => ({label: specimen.displayName, value: specimen}))
    },
    interpretationChoices: function () {
      const positive = {label: this.$t('observation._interpretation.POS'), value: 'POS'};
      const negative = {label: this.$t('observation._interpretation.NEG'), value: 'NEG'};

      let interpretationGroup = this.entity.leadingCode?.interpretationGroup;
      if (interpretationGroup === 'POS' || interpretationGroup === 'TEXT') {
        return [positive]
      }

      if (interpretationGroup === 'POS_NEG') {
        return [positive, negative]
      }

      return [positive, negative]
    },
  },
  methods: {
    defaultChoice: function (currentValue, templateValue, validChoices, required) {
      if (currentValue && validChoices.some(c => c.value === currentValue)) {
        return currentValue
      }

      if (templateValue && validChoices.some(c => c.value === templateValue)) {
        return templateValue
      }

      if (required) {
        return (validChoices[0] ?? null)?.value ?? null
      }

      return null;
    }
  },
  watch: {
    'entity.observation': {
      handler: function (observation) {
        if (!observation) {
          return;
        }

        let availableLeadingCodes = this.leadingCodeChoices.map(lcc => lcc.value)

        // match by organism
        const currentOrganism = this.organisms.find(o => o['@id'] === this.entity.observation?.organism)
        const organismGroups = this.organisms.filter(o => o.code === currentOrganism.code).map(o => o.organismGroup)
        const organismLeadingCode = availableLeadingCodes.filter(lc => organismGroups.includes(lc.organismGroup))
        availableLeadingCodes = organismLeadingCode.length > 0 ? organismLeadingCode : availableLeadingCodes

        // match by specimen
        const specimenLeadingCodes = availableLeadingCodes.filter(lc => lc.specimen === this.probe.specimen)
        availableLeadingCodes = specimenLeadingCodes.length > 0 ? specimenLeadingCodes : availableLeadingCodes

        this.entity.leadingCode = this.defaultChoice(this.entity.leadingCode, availableLeadingCodes[0] ?? null, this.leadingCodeChoices, true)
      }
    },
    'entity.leadingCode': {
      handler: function (leadingCode) {
        if (!leadingCode) {
          return;
        }

        // specimen must be set if either specimen is predefined, or some specimen group is set
        const specimenRequired = leadingCode.specimen || leadingCode.specimenGroup;
        this.fields.specimen.rules = specimenRequired ? [requiredRule] : []

        // take as a default specimen the predefined specimen
        // note that template=null leads to the first (and only) specimen to be set
        const specimenTemplateId = leadingCode.specimen ? null : this.probe.specimen
        const specimenTemplate = this.specimens.find(s => s['@id'] === specimenTemplateId)
        this.entity.specimen = this.defaultChoice(this.entity.specimen, specimenTemplate, this.specimenChoices, specimenRequired)

        const currentOrganism = this.organisms.find(o => o['@id'] === this.entity.observation?.organism)
        this.fields.interpretation.rules = leadingCode.interpretationGroup ? [requiredRule] : []
        if (leadingCode.interpretationGroup === 'TEXT') {
          this.fields.organism.rules = []
          this.entity.organism = null

          this.fields.organismText.rules = [requiredRule]
          this.entity.organismText = currentOrganism?.displayName

          this.fields.interpretation.rules = [requiredRule]
          this.entity.interpretation = 'POS'
        } else {
          this.fields.organismText.rules = []
          this.entity.organismText = null

          const organismRequired = leadingCode.organismGroup;
          this.fields.organism.rules = organismRequired ? [requiredRule] : []
          this.entity.organism = this.defaultChoice(this.entity.organism, currentOrganism, this.organismChoices, organismRequired)

          const interpretationRequired = leadingCode.interpretationGroup
          this.fields.interpretation.rules = interpretationRequired ? [requiredRule] : []
          this.entity.interpretation = leadingCode.interpretationGroup === 'POS' ? 'POS' : (this.entity.observation?.interpretation ?? null)
        }
      }
    }
  },
  mounted() {
    this.entity.observation = this.observations[0] ?? null
  }
}
</script>
