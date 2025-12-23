<template>
  <div>
    <form-field for-id="receivedAt" :label="$t('probe.received_at')" :field="fields.receivedAt">
      <date-time-input id="receivedAt" :field="fields.receivedAt" v-model="entity.receivedAt" format="date"
                       @blur="blurField('receivedAt')" @update:modelValue="validateField('receivedAt')"/>
    </form-field>

    <form-field for-id="analysisStartAt" :label="$t('probe.analysis_start_at')" :field="fields.analysisStartAt">
      <date-time-input id="analysisStartAt" :field="fields.analysisStartAt" v-model="entity.analysisStartAt" format="date"
                       @blur="blurField('analysisStartAt')" @update:modelValue="validateField('analysisStartAt')"/>
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from './utils/form'
import FormField from '../Library/FormLayout/FormField'
import DateTimeInput from '../Library/FormInput/DateTimeInput.vue'

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
        receivedAt: createField(requiredRule),
        analysisStartAt: createField(requiredRule),
      },
      entity: {
        receivedAt: null,
        analysisStartAt: null,
      },
    }
  }
}
</script>
