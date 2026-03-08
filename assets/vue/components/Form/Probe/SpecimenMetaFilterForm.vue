<template>
  <form-field for-id="specimenSource" :label="$t('probe.specimen_source')" :field="fields.pathogen">
    <checkboxes inline id="specimenSource" :choices="specimenSources" :field="fields.specimenSource"
                v-model="entity.specimenSource" @update:model-value="validateField('specimenSource')"/>
  </form-field>
</template>

<script>
import {templatedForm, createField, requiredRule} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField.vue'
import TextInput from '../../Library/FormInput/TextInput.vue'
import TextArea from '../../Library/FormInput/TextArea.vue'
import DateTimeInput from '../../Library/FormInput/DateTimeInput.vue'
import Radio from "../../Library/FormInput/Radio.vue";
import Checkboxes from "../../Library/FormInput/Checkboxes.vue";

const createSpecimenSource = function (translator) {
  const values = ['HUMAN', 'ANIMAL', 'FOOD', 'FEED', 'ENVIRONMENT', 'LABORATORY_STRAIN']
  return values.map(value => ({label: translator(`probe._specimen_source.${value}`), value}))
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
        specimenSource: createField(),
      },
      entity: {
        specimenSource: createField(),
      },
    }
  },
  computed: {
    specimenSources: function () {
      return createSpecimenSource(this.$t)
    },
  },
}

export const specimenMetaFilterFields = ["specimenSource"]
</script>

<style scoped>
.shift-input-up {
  margin-top: -0.8rem;
}
</style>
