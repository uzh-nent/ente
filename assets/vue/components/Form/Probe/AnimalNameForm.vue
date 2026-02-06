<template>
  <form-field for-id="animalName" :label="$t('probe.animal_name')"
              :field="fields.animalName">
    <text-input id="animalName" type="text" :field="fields.animalName"
                v-model="entity.animalName"
                @blur="blurField('animalName')" @update:modelValue="validateField('animalName')"/>
  </form-field>
</template>

<script>
import {templatedForm, createField} from '../utils/form'
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
        animalName: createField(),
      },
      entity: {
        animalName: null,
      },
    }
  }
}
</script>
