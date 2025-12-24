<template>
  <div>
    <form-field for-id="laboratoryFunction" :label="$t('probe.laboratory_function')" :field="fields.ordererIdentifier">
      <radio id="laboratoryFunction" :choices="laboratoryFunctions" :field="fields.laboratoryFunction"
             v-model="entity.laboratoryFunction" @update:model-value="validateField('laboratoryFunction')"/>
    </form-field>
    <template v-if="entity.laboratoryFunction === 'PRIMARY'">
      <form-field for-id="analysisTypes" :label="$t('service.ecoli_identification')" :field="fields.analysisTypes">
        <checkboxes id="analysisTypes" :choices="primaryAnalysisTypes" :field="fields.analysisTypes"
                    v-model="entity.analysisTypes" @update:model-value="validateField('analysisTypes')"/>
      </form-field>
    </template>
    <template v-if="entity.laboratoryFunction === 'REFERENCE'">
      <form-field for-id="pathogen" :label="$t('service.identification_typing')" :field="fields.pathogen"
                  :fake-required="true">
        <radio id="pathogen" :choices="referencePathogens"
               :field="fields.pathogen"
               v-model="entity.pathogen" @update:model-value="validateField('pathogen')"/>
      </form-field>
      <template v-if="entity.pathogen === null">
        <text-input id="pathogenText" type="text" class="shift-input-up" :field="fields.pathogenText"
                    v-model="entity.pathogenText"
                    @blur="blurField('pathogenText')" @update:modelValue="validateField('pathogenText')"/>
      </template>
    </template>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField.vue'
import TextInput from '../../Library/FormInput/TextInput.vue'
import TextArea from '../../Library/FormInput/TextArea.vue'
import DateTimeInput from '../../Library/FormInput/DateTimeInput.vue'
import Radio from "../../Library/FormInput/Radio.vue";
import Checkboxes from "../../Library/FormInput/Checkboxes.vue";

const createLaboratoryFunctions = function (translator) {
  const values = ['REFERENCE', 'PRIMARY']
  return values.map(value => ({label: translator(`probe._laboratory_function.${value}`), value}))
}

const createReferencePathogens = function (translator) {
  const values = ['SALMONELLA', 'SHIGELLA', 'YERSINIA', 'LISTERIA_MONOCYTOGENES', 'VIBRIO_CHOLERAE', 'ESCHERICHIA_COLI', 'CAMPYLOBACTER']
  return values
      .map(value => ({label: translator(`probe._pathogen.${value}`), value}))
      .concat({label: translator('probe._pathogen.OTHER'), value: null})
}

const createPrimaryAnalysisTypes = function (translator) {
  const values = ['EC_STEC', 'EC_EPEC', 'EC_ETEC', 'EC_EIEC', 'EC_EAEC']
  return values.map(value => ({label: translator(`probe._analysis_type.${value}`), value}))
}
export default {
  emits: ['update'],
  components: {
    Checkboxes,
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
        laboratoryFunction: createField(requiredRule),
        pathogen: createField(),
        pathogenText: createField(),
        analysisTypes: createField(requiredRule),
      },
      entity: {
        laboratoryFunction: null,
        pathogen: null,
        pathogenText: null,
        analysisTypes: null,
      },

      searchName: "",
      searchPostalCode: "",
    }
  },
  computed: {
    laboratoryFunctions: function () {
      return createLaboratoryFunctions(this.$t)
    },
    referencePathogens: function () {
      return createReferencePathogens(this.$t)
    },
    primaryAnalysisTypes: function () {
      return createPrimaryAnalysisTypes(this.$t)
    },
  },
  watch: {
    'entity.laboratoryFunction': {
      immediate: true,
      handler: function (laboratoryFunction) {
        if (laboratoryFunction === 'REFERENCE') {
          this.entity.pathogen = 'SALMONELLA'
          this.entity.pathogenText = null
          this.entity.analysisTypes = ['IDENTIFICATION']
        }

        if (laboratoryFunction === 'PRIMARY') {
          this.entity.pathogen = 'ESCHERICHIA_COLI'
          this.entity.pathogenText = null
          this.entity.analysisTypes = []
        }
      },
    }
  }
}
</script>

<style scoped>
.shift-input-up {
  margin-top: -0.8rem;
}
</style>
