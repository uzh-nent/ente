<template>

  <div class="row">
    <div class="col-md-6">
      <form-field for-id="analysisStopAt" :label="$t('observation.analysis_stop_at')" :field="fields.analysisStopAt">
        <date-time-input
            id="analysisStopAt" format="date" :field="fields.analysisStopAt" v-model="entity.analysisStopAt"
            @blur="blurField('analysisStopAt')" @update:modelValue="validateField('analysisStopAt')"/>
      </form-field>
    </div>
  </div>

  <hr/>

  <div class="row">
    <div class="col-md-6">
      <form-field for-id="identificationSuccessful" :label="$t('observation.identification_successful')"
                  :field="fields.identificationSuccessful">
        <checkbox id="identificationSuccessful" :field="fields.identificationSuccessful"
                  v-model="entity.identificationSuccessful"
                  @update:model-value="validateField('identificationSuccessful')"/>
      </form-field>
    </div>
    <div class="col-md-12" v-if="entity.identificationSuccessful">
      <form-field for-id="searchOrganism" :label="$t('observation.organism')" :field="fields.identification">
        <text-input id="searchOrganism" type="text" v-model="entity.searchOrganism"/>

        <radio id="animalKeeper" :choices="maxedFilteredOrganisms" :field="fields.organism"
               :value-string="value => value['@id']"
               v-model="entity.organism" @update:model-value="validateField('organism')"/>

        <custom-select class="mt-1" id="organism" :choices="filteredOrganisms" :field="fields.organism"
                       v-model="entity.organism" @update:model-value="validateField('organism')"/>
        <span class="form-text">{{ itemHits }}</span>
      </form-field>
    </div>
    <div class="col-md-12">
      <form-field for-id="interpretationText" :label="$t('address.interpretation_text')"
                  :field="fields.interpretationText">
        <text-input id="interpretationText" :field="fields.interpretationText" v-model="entity.interpretationText"
                    @blur="blurField('interpretationText')" @update:modelValue="validateField('interpretationText')"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule, countryCode} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextInput from '../../Library/FormInput/TextInput.vue'
import TextArea from '../../Library/FormInput/TextArea.vue'
import DateTimeInput from '../../Library/FormInput/DateTimeInput.vue'
import Radio from "../../Library/FormInput/Radio.vue";
import CustomSelect from "../../Library/FormInput/CustomSelect.vue";
import Checkbox from "../../Library/FormInput/Checkbox.vue";
import debounce from "lodash.debounce";
import {sortOrganisms} from "../../../services/domain/sorters";

export default {
  emits: ['update'],
  components: {
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
        analysisStopAt: createField(requiredRule),
        identificationSuccessful: createField(),

        organism: createField(),
        interpretationText: createField(),
      },
      entity: {
        analysisStopAt: null,
        identificationSuccessful: null,

        organism: null,
        interpretationText: null,
      },

      searchOrganism: '',
      filteredOrganisms: null,
      filteredOrganismsTerm: ''
    }
  },
  props: {
    pathogen: {
      type: String,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  },
  computed: {
    potentialOrganisms: function () {
      const organisms = this.organisms.filter(o => o.pathogen === this.pathogen)
      sortOrganisms(organisms)

      return organisms
    },
    maxedFilteredOrganisms: function () {
      return this.filteredOrganisms?.slice(0, 10) ?? []
    },
    itemHits: function () {
      let hits = this.maxedFilteredOrganisms.length;
      if (this.maxedFilteredOrganisms.length < this.filteredOrganisms?.length) {
        hits = `${hits}+`
      }

      return this.$t('_action.hits', {hits: hits})
    }
  },
  methods: {
    filterOrganisms: function (searchOrganism) {
      let extendsPreviousSearch = this.filteredOrganismsTerm && this.filteredOrganisms && searchOrganism.includes(this.filteredOrganismsTerm);
      const base = [...(extendsPreviousSearch ? this.filteredOrganisms : this.potentialOrganisms)]
      const keywords = searchOrganism.split(" ").map(kw => kw.toLowerCase())
      this.filteredOrganisms = base.filter(o => {
        const match = o.displayName.toLowerCase()
        return keywords.all(kw => match.includes(kw))
      })
      this.filteredOrganismsTerm = searchOrganism
    }
  },
  watch: {
    searchOrganism: {
      immediate: true,
      handler: debounce(function (searchOrganism) {
        this.filterOrganisms(searchOrganism)
      }, 200, {'leading': true}),
    },
    'entity.identificationSuccessful': {
      immediate: true,
      handler: function (identificationSuccessful) {
        if (identificationSuccessful) {
          this.fields.organism.rules = [requiredRule]
        } else {
          this.searchOrganism = ''
          this.entity.organism = null
          this.fields.organism.rules = []
        }
      },
    }
  }
}
</script>
