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
      <form-field for-id="pathogen" :label="$t('service.identification_typing')" :field="fields.pathogen" :fake-required="true">
        <radio id="pathogen" :choices="referencePathogens"
               :field="fields.pathogen"
               v-model="entity.pathogen" @update:model-value="validateField('pathogen')"/>
      </form-field>
      <template v-if="entity.pathogen === null">
        <form-field for-id="pathogenText" :label="$t('probe.pathogen_text')"
                    :field="fields.pathogenText">
          <text-input id="pathogenText" type="text" :field="fields.pathogenText"
                      v-model="entity.pathogenText"
                      @blur="blurField('pathogenText')" @update:modelValue="validateField('pathogenText')"/>
        </form-field>
      </template>
    </template>

    <form-field for-id="receivedAt" :label="$t('probe.received_at')" :field="fields.receivedAt">
      <date-time-input id="receivedAt" :field="fields.receivedAt" v-model="entity.receivedAt" format="date"
                       @blur="blurField('receivedAt')" @update:modelValue="validateField('receivedAt')"/>
    </form-field>
    <form-field for-id="ordererIdentifier" :label="$t('probe.orderer_identifier')" :field="fields.ordererIdentifier">
      <text-input id="ordererIdentifier" type="text" :field="fields.ordererIdentifier"
                  v-model="entity.ordererIdentifier"
                  @blur="blurField('ordererIdentifier')" @update:modelValue="validateField('ordererIdentifier')"/>
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from './utils/form'
import FormField from '../Library/FormLayout/FormField'
import TextInput from '../Library/FormInput/TextInput.vue'
import TextArea from '../Library/FormInput/TextArea.vue'
import DateTimeInput from '../Library/FormInput/DateTimeInput.vue'
import CustomSelect from '../Library/FormInput/CustomSelect.vue'
import Radio from "../Library/FormInput/Radio.vue";
import Checkboxes from "../Library/FormInput/Checkboxes.vue";

const createLaboratoryFunctions = function (translator) {
  return [
    {label: translator('probe._laboratory_function.REFERENCE'), value: 'REFERENCE'},
    {label: translator('probe._laboratory_function.PRIMARY'), value: 'PRIMARY'},
  ]
}

const createReferencePathogens = function (translator) {
  return [
    {label: translator('probe._pathogen.SALMONELLA'), value: 'SALMONELLA'},
    {label: translator('probe._pathogen.SHIGELLA'), value: 'SHIGELLA'},
    {label: translator('probe._pathogen.YERSINIA'), value: 'YERSINIA'},
    {label: translator('probe._pathogen.LISTERIA_MONOCYTOGENES'), value: 'LISTERIA_MONOCYTOGENES'},
    {label: translator('probe._pathogen.VIBRIO_CHOLERAE'), value: 'VIBRIO_CHOLERAE'},
    {label: translator('probe._pathogen.ESCHERICHIA_COLI'), value: 'ESCHERICHIA_COLI'},
    {label: translator('probe._pathogen.CAMPYLOBACTER'), value: 'CAMPYLOBACTER'},
    {label: translator('probe._pathogen.OTHER'), value: null},
  ]
}

const createPrimaryAnalysisTypes = function (translator) {
  return [
    {label: translator('observation._analysis_type.EC_STEC'), value: 'EC_STEC'},
    {label: translator('observation._analysis_type.EC_EPEC'), value: 'EC_EPEC'},
    {label: translator('observation._analysis_type.EC_ETEC'), value: 'EC_ETEC'},
    {label: translator('observation._analysis_type.EC_EIEC'), value: 'EC_EIEC'},
    {label: translator('observation._analysis_type.EC_EAEC'), value: 'EC_EAEC'},
  ]
}
export default {
  emits: ['update'],
  components: {
    Checkboxes,
    Radio,
    CustomSelect,
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
        analysisTypes: createField(),

        ordererIdentifier: createField(requiredRule),
        receivedAt: createField(requiredRule),
      },
      entity: {
        laboratoryFunction: null,
        pathogen: null,
        pathogenText: null,
        analysisTypes: null,

        ordererIdentifier: null,
        receivedAt: null,
      }
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
    }
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
