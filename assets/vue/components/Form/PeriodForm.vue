<template>
  <div>
    <form-field for-id="period" :label="$t('_form.period')">
      <date-time-span-input
          id="period" format="date"
          v-model:after="entity['period[after]']"
          v-model:before="entity['period[before]']"
      />
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField} from './utils/form'
import FormField from './../Library/FormLayout/FormField.vue'
import DateTimeInput from './../Library/FormInput/DateTimeInput.vue'
import DateTimeSpanInput from "./../Library/FormInput/DateTimeSpanInput.vue";

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
        'period[after]': createField(),
        'period[before]': createField(),
      },
      entity: {
        'period[after]': null,
        'period[before]': null,
      },
    }
  }
}

export const periodFields = ["period[before]", "period[after]"]
</script>
