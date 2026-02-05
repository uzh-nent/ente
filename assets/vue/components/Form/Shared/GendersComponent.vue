<template>
  <checkboxes inline id="gender" :choices="genderChoices" :field="fields.gender"
         v-model="entity.gender" @update:model-value="validateField('gender')"/>
</template>

<script>
import {templatedForm, createField} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextArea from "../../Library/FormInput/TextArea.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";
import Radio from "../../Library/FormInput/Radio.vue";
import Checkboxes from "../../Library/FormInput/Checkboxes.vue";

const createGenderChoices = function (translator) {
  const values =['MALE', 'FEMALE', 'OTHER']
  return values
      .map(value => ({label: translator(`patient._gender.${value}`), value}))
      .concat({label: translator('patient._gender.UNKNOWN'), value: null})
}

export default {
  emits: ['update'],
  components: {
    Checkboxes,
    Radio,
    TextInput,
    TextArea,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        gender: createField(),
      },
      entity: {
        gender: null,
      }
    }
  },
  computed: {
    genderChoices: function () {
      return createGenderChoices(this.$t)
    }
  },
}

export const gendersFields = ["gender"]
</script>
