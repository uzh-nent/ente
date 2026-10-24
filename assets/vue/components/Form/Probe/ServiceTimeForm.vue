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

    <form-field for-id="analysisStopDate" :label="$t('probe.analysis_stop_date')" :field="fields.analysisStopDate">
      <date-time-input id="analysisStopDate" :field="fields.analysisStopDate" v-model="entity.analysisStopDate" format="date"
                       @blur="blurField('analysisStopDate')" @update:modelValue="validateField('analysisStopDate')"/>
    </form-field>

    <hr/>
    <form-field for-id="comment" :label="$t('probe.comment')"
                :field="fields.comment">
      <text-area
          id="comment" :field="fields.comment" v-model="entity.comment"
          @blur="blurField('comment')" @update:modelValue="validateField('comment')"/>
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField.vue'
import DateTimeInput from '../../Library/FormInput/DateTimeInput.vue'
import TextAreaWithStandardText from "../../Library/FormInput/TextAreaWithStandardText.vue";
import TextArea from "../../Library/FormInput/TextArea.vue";

export default {
  emits: ['update'],
  components: {
    TextArea,
    TextAreaWithStandardText,
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
        analysisStopDate: createField(),
        comment: createField(),
      },
      entity: {
        receivedDate: null,
        analysisStartDate: null,
        analysisStopDate: null,
        comment: null,
      },
    }
  }
}
</script>
