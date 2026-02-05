<template>
  <div>
    <form-field for-id="laboratoryFunction" :label="$t('probe.laboratory_function')"
                :field="fields.laboratoryFunction">
      <checkboxes inline id="laboratoryFunction" :choices="laboratoryFunctions" :field="fields.laboratoryFunction"
                  v-model="entity.laboratoryFunction" @update:model-value="validateField('laboratoryFunction')"/>
    </form-field>
    <form-field for-id="pathogen" :label="$t('service.identification_typing')" :field="fields.pathogen">
      <checkboxes inline id="pathogen" :choices="pathogens" :field="fields.pathogen"
                  v-model="entity.pathogen" @update:model-value="validateField('pathogen')"/>
    </form-field>
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

const createPathogens = function (translator) {
  const values = ['SALMONELLA', 'SHIGELLA', 'YERSINIA', 'LISTERIA_MONOCYTOGENES', 'VIBRIO_CHOLERAE', 'ESCHERICHIA_COLI', 'CAMPYLOBACTER']
  return values
      .map(value => ({label: translator(`probe._pathogen.${value}`), value}))
      .concat({label: translator('probe._pathogen.OTHER'), value: null})
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
        laboratoryFunction: createField(),
        pathogen: createField(),
      },
      entity: {
        laboratoryFunction: null,
        pathogen: null,
      },
    }
  },
  computed: {
    laboratoryFunctions: function () {
      return createLaboratoryFunctions(this.$t)
    },
    pathogens: function () {
      return createPathogens(this.$t)
    },
  },
}

export const serviceRequestFilterFields = ["laboratoryFunction", "pathogen"]
</script>

<style scoped>
.shift-input-up {
  margin-top: -0.8rem;
}
</style>
