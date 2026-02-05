<template>
  <div>
    <form-field for-id="analysisStartDate" :label="$t('probe.analysis_start_date')">
      <date-time-span-input
          id="analysisStartDate" format="date"
          v-model:before="entity['analysisStartDate[before]']"
          v-model:after="entity['analysisStartDate[after]']"
      />
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField.vue'
import DateTimeInput from '../../Library/FormInput/DateTimeInput.vue'
import DateTimeSpanInput from "../../Library/FormInput/DateTimeSpanInput.vue";

export default {
  emits: ['update'],
  components: {
    DateTimeSpanInput,
    DateTimeInput,
    FormField
  },
  mixins: [
    templatedForm,
  ],
  data() {
    return {
      fields: {
        'analysisStartDate[before]': createField(),
        'analysisStartDate[after]': createField(),
      },
      entity: {
        'analysisStartDate[before]': null,
        'analysisStartDate[after]': null,
      },
    }
  }
}

export const serviceTimeSpanFields = ["analysisStartDate[before]", "analysisStartDate[after]"]
</script>
