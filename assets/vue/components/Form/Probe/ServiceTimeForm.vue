<template>
  <div>
    <form-field for-id="receivedDate" :label="$t('probe.received_date')" :field="fields.receivedDate">
      <date-time-input id="receivedDate" :field="fields.receivedDate" v-model="entity.receivedDate" format="date"
                       @blur="blurField('receivedDate')" @update:modelValue="validateField('receivedDate')"/>
    </form-field>

    <form-field for-id="analysisStartDate" :label="$t('probe.analysis_start_date')" :field="fields.analysisStartDate">
      <date-time-input id="analysisStartDate" :field="fields.analysisStartDate" v-model="entity.analysisStartDate" format="date"
                       @blur="blurField('analysisStartDate')" @update:modelValue="validateField('analysisStartDate')"/>
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField.vue'
import DateTimeInput from '../../Library/FormInput/DateTimeInput.vue'

export default {
  emits: ['update'],
  components: {
    DateTimeInput,
    FormField
  },
  mixins: [
    templatedForm,
  ],
  data() {
    return {
      fields: {
        receivedDate: createField(requiredRule),
        analysisStartDate: createField(requiredRule),
      },
      entity: {
        receivedDate: null,
        analysisStartDate: null,
      },
    }
  }
}
</script>
