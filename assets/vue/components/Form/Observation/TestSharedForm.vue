<template>
  <div class="row">
    <div class="col-md-6">
      <form-field for-id="effectiveAt" :label="$t('observation.effective_at')" :field="fields.effectiveAt">
        <date-time-input
            id="effectiveAt" format="datetime" :field="fields.effectiveAt" v-model="entity.effectiveAt"
            @blur="blurField('effectiveAt')" @update:modelValue="validateField('effectiveAt')"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule, countryCode} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import DateTimeInput from '../../Library/FormInput/DateTimeInput.vue'

export default {
  emits: ['update'],
  components: {
    DateTimeInput,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        effectiveAt: createField(requiredRule),
      },
      entity: {
        effectiveAt: null,
      },
    }
  },
}
</script>
