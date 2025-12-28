<template>

  <form-field for-id="observation" :label="$t('elm_report.observation')" :field="fields.observation">
    <custom-select id="observation" :choices="observationChoices" :field="fields.observation"
                   v-model="entity.observation" @update:model-value="validateField('observation')"/>
  </form-field>

  <actionable-preview>
    <identification-view v-if="entity.observation?.analysisType === 'IDENTIFICATION'"
                         :organisms="organisms" :observation="entity.observation"/>
  </actionable-preview>

  <hr/>

  <form-field for-id="leadingCode" :label="$t('elm_report.leading_code')" :field="fields.leadingCode">
    <custom-select id="leadingCode" :choices="leadingCodeChoices" :field="fields.leadingCode"
                   v-model="entity.leadingCode" @update:model-value="validateField('leadingCode')"/>
  </form-field>

  <form-field for-id="organism" :label="$t('elm_report.organism')" :field="fields.organism">
    <custom-select id="organism" :choices="organismChoices" :field="fields.organism"
                   v-model="entity.organism" @update:model-value="validateField('organism')"/>
    <text-area id="organismText" :field="fields.organismText" v-model="entity.organismText"
               @blur="blurField('organismText')" @update:modelValue="validateField('organismText')"/>
  </form-field>

  <form-field for-id="specimen" :label="$t('elm_report.specimen')" :field="fields.specimen">
    <custom-select id="specimen" :choices="specimenChoices" :field="fields.specimen"
                   v-model="entity.specimen" @update:model-value="validateField('specimen')"/>
  </form-field>

  <form-field for-id="interpretation" :label="$t('elm_report.interpretation')" :field="fields.interpretation">
    <custom-select id="interpretation" :choices="interpretationChoices" :field="fields.interpretation"
                   v-model="entity.interpretation" @update:model-value="validateField('interpretation')"/>
  </form-field>
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

const SEARCH_CUTOFF = 10

export default {
  emits: ['update'],
  components: {
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
    pathogen: {
      type: String,
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
      return this.observations.map(o => ({label: formatObservation(o), value: o}))
    },
    leadingCodeChoices: function () {
      const filtered = this.leadingCodes.filter(lc => lc.pathogen === this.pathogen)
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
  watch: {
    'entity.observation': {
      handler: function (observation) {
        if (!observation) {
          return;
        }

        this.entity.leadingCode = this.leadingCodeChoices[0].value
      }
    },
    'entity.leadingCode': {
      handler: function (leadingCode) {
        if (!leadingCode) {
          return;
        }

        this.entity.organism.rules = leadingCode.organismGroup ? [requiredRule] : []
        this.entity.specimen.rules = leadingCode.specimenGroup ? [requiredRule] : []
        this.entity.interpretation.rules = leadingCode.interpretationGroup ? [requiredRule] : []

        if (leadingCode.interpretationGroup === 'TEXT') {
          this.entity.organism.rules = []
          this.entity.organismText.rules = [requiredRule]
        } else {
          this.entity.organismText.rules = []
        }
      }
    }
  },
}
</script>
