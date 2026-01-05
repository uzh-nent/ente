<template>
  <form-field for-id="name" :label="label" :field="fields.name">
    <text-area id="name" :field="fields.name" v-model="entity.name"
               @blur="blurField('name')" @update:modelValue="validateField('name')"/>
  </form-field>
</template>

<script>
import {templatedForm, createField, requiredRule} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextArea from "../../Library/FormInput/TextArea.vue";

export default {
  emits: ['update'],
  components: {
    TextArea,
    FormField
  },
  props: {
    label: {
      type: String,
      required: true
    }
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        name: createField(requiredRule),
      },
      entity: {
        name: null,
      }
    }
  }
}

export const thingFields = ["name"]
</script>
