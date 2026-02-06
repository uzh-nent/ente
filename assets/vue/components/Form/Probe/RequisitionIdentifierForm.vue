<template>
  <form-field for-id="requisitionIdentifier" :label="$t('probe.requisition_identifier')"
              :field="fields.requisitionIdentifier">
    <text-input id="requisitionIdentifier" type="text" :field="fields.requisitionIdentifier"
                v-model="entity.requisitionIdentifier"
                @blur="blurField('requisitionIdentifier')" @update:modelValue="validateField('requisitionIdentifier')"/>
  </form-field>
</template>

<script>
import {templatedForm, createField, requiredRule} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField.vue'
import TextInput from "../../Library/FormInput/TextInput.vue";

export default {
  emits: ['update'],
  components: {
    TextInput,
    FormField
  },
  mixins: [
    templatedForm,
  ],
  data() {
    return {
      fields: {
        requisitionIdentifier: createField(requiredRule),
      },
      entity: {
        requisitionIdentifier: null,
      },
    }
  },
}
</script>
