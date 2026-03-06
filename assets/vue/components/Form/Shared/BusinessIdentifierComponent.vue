<template>
  <div class="row">
    <div class="col-md-6">
      <form-field for-id="ber" :label="$t('business_identifier.ber')" :field="fields.ber">
        <text-input id="ber" type="text" :field="fields.ber" v-model="entity.ber"
                    @blur="blurField('ber')" @update:modelValue="validateField('ber')"/>
      </form-field>
    </div>
    <div class="col-md-6">
      <form-field for-id="uid" :label="$t('business_identifier.uid')" :field="fields.uid">
        <uid-number-input id="uid" type="text" :field="fields.uid" v-model="entity.uid"
                    @blur="blurField('uid')" @update:modelValue="validateField('uid')"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {
  templatedForm,
  createField,
  ahvNumberLengthRule,
  uidNumberLengthRule,
  uidNumberCheckRule,
  berNumberLengthRule
} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextInput from "../../Library/FormInput/TextInput.vue";
import UidNumberInput from "../../Library/FormInput/UidNumberInput.vue";

export default {
  emits: ['update'],
  components: {
    UidNumberInput,
    TextInput,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        ber: createField(berNumberLengthRule),
        uid: createField(uidNumberLengthRule,uidNumberCheckRule),
      },
      entity: {
        ber: null,
        uid: null,
      }
    }
  },
}

export const businessIdentifierFields = ["ber", "uid"]
</script>
