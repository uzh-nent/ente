<template>
  <form-field for-id="contact" :label="$t('contact.contact')" :field="fields.contact">
    <text-area id="contact" :field="fields.contact" v-model="entity.contact"
               @blur="blurField('contact')" @update:modelValue="validateField('contact')"/>
  </form-field>
</template>

<script>
import {templatedForm, createField} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextArea from "../../Library/FormInput/TextArea.vue";

export default {
  emits: ['update'],
  components: {
    TextArea,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        contact: createField(),
      },
      entity: {
        contact: null,
      }
    }
  }
}

export const contactFields = ["contact"]
</script>
