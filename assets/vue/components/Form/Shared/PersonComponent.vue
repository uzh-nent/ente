<template>
  <div class="row">
    <div class="col-md-6">
      <form-field for-id="familyName" :label="$t('person.family_name')" :field="fields.familyName">
        <text-input id="familyName" type="text" :field="fields.familyName" v-model="entity.familyName"
                    @blur="blurField('familyName')" @update:modelValue="validateField('familyName')"/>
      </form-field>
    </div>
    <div class="col-md-6">
      <form-field for-id="givenName" :label="$t('person.given_name')" :field="fields.givenName">
        <text-input id="givenName" type="text" :field="fields.givenName" v-model="entity.givenName"
                    @blur="blurField('givenName')" @update:modelValue="validateField('givenName')"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {templatedForm, createField} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextArea from "../../Library/FormInput/TextArea.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";

export default {
  emits: ['update'],
  components: {
    TextInput,
    TextArea,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        givenName: createField(),
        familyName: createField(),
      },
      entity: {
        givenName: null,
        familyName: null,
      }
    }
  }
}

export const personFields = ["givenName", "familyName"]
</script>
