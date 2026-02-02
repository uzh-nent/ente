<template>
  <form-field for-id="title" :label="$t('practitioner.title')" :field="fields.title">
    <text-input id="title" type="text" :field="fields.title" v-model="entity.title"
                @blur="blurField('title')" @update:modelValue="validateField('title')"/>
  </form-field>
</template>

<script>
import {templatedForm, createField} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextInput from "../../Library/FormInput/TextInput.vue";

export default {
  emits: ['update'],
  components: {
    TextInput,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        title: createField(),
      },
      entity: {
        title: null,
      }
    }
  },
}

export const titleFields = ["title"]
</script>
