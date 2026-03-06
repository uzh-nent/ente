<template>
  <form-field for-id="gln" :label="$t('practitioner.gln')" :field="fields.gln">
    <text-input id="gln" type="text" :field="fields.gln" v-model="entity.gln"
                @blur="blurField('gln')" @update:modelValue="validateField('gln')"/>
  </form-field>
</template>

<script>
import {templatedForm, createField, glnNumberLengthRule, glnNumberCheckRule} from '../utils/form'
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
        gln: createField(glnNumberLengthRule, glnNumberCheckRule),
      },
      entity: {
        gln: null,
      }
    }
  },
}

export const glnFields = ["gln"]
</script>
