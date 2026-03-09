<template>
  <div class="row">
    <div class="col-md-6">
      <form-field for-id="probeIdentifiers" :label="$t('probe.identifiers')" :field="fields.probeIdentifiers">
        <text-area id="probeIdentifiers" :rows="20" :disabled="disabled"
                   :field="fields.probeIdentifiers" v-model="entity.probeIdentifiers"
                   @blur="blurField('probeIdentifiers')" @update:modelValue="validateField('probeIdentifiers')"/>
      </form-field>
    </div>
    <div class="col-md-6">
      <form-field for-id="invoiceIdentifiers" :label="$t('invoice.invoice_identifiers')"
                  :field="fields.invoiceIdentifiers">
        <text-area id="invoiceIdentifiers" :rows="20" :disabled="disabled"
                   :field="fields.invoiceIdentifiers" v-model="entity.invoiceIdentifiers"
                   @blur="blurField('invoiceIdentifiers')" @update:modelValue="validateField('invoiceIdentifiers')"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from './utils/form'
import FormField from './../Library/FormLayout/FormField.vue'
import DateTimeInput from './../Library/FormInput/DateTimeInput.vue'
import DateTimeSpanInput from "./../Library/FormInput/DateTimeSpanInput.vue";
import TextArea from "../Library/FormInput/TextArea.vue";

export default {
  components: {
    TextArea,
    FormField
  },
  mixins: [templatedForm],
  props: {
    disabled: {
      type: Boolean,
      default: false
    },
  },
  data() {
    return {
      fields: {
        probeIdentifiers: createField(requiredRule),
        invoiceIdentifiers: createField(requiredRule),
      },
      entity: {
        probeIdentifiers: null,
        invoiceIdentifiers: null,
      },
    }
  }
}

</script>
