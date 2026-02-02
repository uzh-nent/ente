<template>
  <div class="row">
    <div class="col-md-6">
      <form-field for-id="birthDate" :label="$t('patient.birth_date')" :field="fields.birthDate">
        <date-time-input id="birthDate" :field="fields.birthDate" v-model="entity.birthDate" format="date"
                         @blur="blurField('birthDate')" @update:modelValue="validateField('birthDate')"/>
      </form-field>
    </div>
    <div class="col-md-6">
      <form-field for-id="ahvNumber" :label="$t('patient.ahv_number')" :field="fields.ahvNumber">
        <ahv-number-input id="ahvNumber" type="text" :field="fields.ahvNumber" v-model="entity.ahvNumber"
                          @blur="blurField('ahvNumber')" @update:modelValue="validateField('ahvNumber')"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {
  templatedForm,
  createField,
  requiredRule,
  ahvNumberCheckRule,
  ahvNumberLengthRule
} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextArea from "../../Library/FormInput/TextArea.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";
import DateTimeInput from "../../Library/FormInput/DateTimeInput.vue";
import AhvNumberInput from "../../Library/FormInput/AhvNumberInput.vue";

export default {
  emits: ['update'],
  components: {
    AhvNumberInput,
    DateTimeInput,
    TextInput,
    TextArea,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        birthDate: createField(requiredRule),
        ahvNumber: createField(ahvNumberLengthRule,ahvNumberCheckRule),
      },
      entity: {
        birthDate: null,
        ahvNumber: null,
      }
    }
  },
}

export const patientFields = ["birthDate", "ahvNumber"]
</script>
