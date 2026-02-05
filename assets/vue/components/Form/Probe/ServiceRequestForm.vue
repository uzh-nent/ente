<template>
  <div>
    <form-field for-id="laboratoryFunction" :label="$t('probe.laboratory_function')"
                :field="fields.laboratoryFunction">
      <radio id="laboratoryFunction" :choices="laboratoryFunctions" :field="fields.laboratoryFunction"
             :disabled="editMode"
             v-model="entity.laboratoryFunction" @update:model-value="validateField('laboratoryFunction')"/>
    </form-field>
    <template v-if="entity.laboratoryFunction === 'PRIMARY'">
      <form-field for-id="analysisTypes" :label="$t('service.ecoli_identification')" :field="fields.analysisTypes">
        <checkboxes id="analysisTypes" :choices="escherichiaColiAnalysisTypes" :field="fields.analysisTypes"
                    v-model="entity.analysisTypes" @update:model-value="validateField('analysisTypes')"/>
      </form-field>
    </template>
    <template v-if="entity.laboratoryFunction === 'REFERENCE'">
      <form-field for-id="pathogen" :label="$t('service.identification_typing')" :field="fields.pathogen"
                  :fake-required="true">

        <div v-for="choice in referencePathogens" :key="choice.value">
          <div class="form-check">
            <input class="form-check-input" type="radio"
                   name="pathogen" :id="'pathogen_' + choice.value" :value="choice.value"
                   :checked="choice.value === entity.pathogen"
                   @change="$event.target.checked ? entity.pathogen = choice.value : null">
            <label class="form-check-label clickable" :for="'pathogen_' + choice.value">{{ choice.label }}</label>
          </div>
          <template v-if="choice.value === 'VIBRIO_CHOLERAE' && choice.value === entity.pathogen">
            <checkboxes id="analysisTypes" class="mb-2" :choices="referenceVibrioCholeraeAnalysisTypes" :field="fields.analysisTypes"
                        :disabled="value => value === 'IDENTIFICATION'"
                        v-model="entity.analysisTypes" @update:model-value="validateField('analysisTypes')"/>
          </template>
          <template v-if="choice.value === 'ESCHERICHIA_COLI' && choice.value === entity.pathogen">
            <checkboxes id="analysisTypes" class="mb-2" :choices="escherichiaColiAnalysisTypes" :field="fields.analysisTypes"
                        v-model="entity.analysisTypes" @update:model-value="validateField('analysisTypes')"/>
          </template>
        </div>
      </form-field>
      <template v-if="entity.pathogen == null">
        <text-input id="pathogenName" type="text" class="shift-input-up" :field="fields.pathogenName"
                    v-model="entity.pathogenName"
                    @blur="blurField('pathogenName')" @update:modelValue="validateField('pathogenName')"/>
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

const createEscherichiaColiAnalysisTypes = function (translator) {
  const values = ['EC_STEC', 'EC_EPEC', 'EC_ETEC', 'EC_EIEC', 'EC_EAEC']
  return values.map(value => ({label: translator(`probe._analysis_type.${value}`), value}))
}

const createReferenceVibrioCholeraeAnalysisTypes = function (translator) {
  const values = ['IDENTIFICATION', 'VB_TOXIN']
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
        pathogenName: createField(),
        analysisTypes: createField(requiredRule),
      },
      entity: {
        laboratoryFunction: null,
        pathogen: null,
        pathogenName: null,
        analysisTypes: null,
      },
    }
  },
  props: {
    editMode: {
      type: Boolean,
      default: false
    },
  },
  computed: {
    laboratoryFunctions: function () {
      return createLaboratoryFunctions(this.$t)
    },
    referencePathogens: function () {
      return createReferencePathogens(this.$t)
    },
    escherichiaColiAnalysisTypes: function () {
      return createEscherichiaColiAnalysisTypes(this.$t)
    },
    referenceVibrioCholeraeAnalysisTypes: function () {
      return createReferenceVibrioCholeraeAnalysisTypes(this.$t)
    },
  },
  watch: {
    'entity.laboratoryFunction': {
      handler: function (laboratoryFunction) {
        if (laboratoryFunction === 'REFERENCE') {
          this.entity.pathogen = 'SALMONELLA'
          this.entity.pathogenName = null
          this.entity.analysisTypes = ['IDENTIFICATION']

          document.getElementById("next-reference-identifier").classList.remove("d-none")
          document.getElementById("next-primary-identifier").classList.add("d-none")
        }

        if (laboratoryFunction === 'PRIMARY') {
          this.entity.pathogen = 'ESCHERICHIA_COLI'
          this.entity.pathogenName = null
          this.entity.analysisTypes = []

          document.getElementById("next-primary-identifier").classList.remove("d-none")
          document.getElementById("next-reference-identifier").classList.add("d-none")
        }
      },
    },
    'entity.pathogen': {
      handler: function (pathogen) {
        if (this.entity.laboratoryFunction === 'REFERENCE') {
          if (pathogen === 'VIBRIO_CHOLERAE') {
            this.entity.analysisTypes = ['IDENTIFICATION', 'VB_TOXIN']
          } else if (pathogen === 'ESCHERICHIA_COLI') {
            this.entity.analysisTypes = ["EC_STEC", "EC_EPEC", "EC_ETEC", "EC_EIEC"]
          } else {
            this.entity.analysisTypes = ["IDENTIFICATION"]
          }
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
