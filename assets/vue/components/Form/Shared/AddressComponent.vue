<template>
  <div class="row">
    <div class="col-md-2">
      <form-field for-id="countryCode" :label="$t('address.country_code_short')" :field="fields.countryCode">
        <text-input id="countryCode" type="text" :field="fields.countryCode" v-model="entity.countryCode"
                    @blur="blurField('countryCode')" @update:modelValue="validateField('countryCode')"/>
      </form-field>
    </div>
    <div class="col-md-3">
      <form-field for-id="postalCode" :label="$t('address.postal_code')" :field="fields.postalCode">
        <text-input id="postalCode" type="text" :field="fields.postalCode" v-model="entity.postalCode"
                    @blur="blurField('postalCode')" @update:modelValue="validateField('postalCode')"/>
      </form-field>
    </div>
    <div class="col-md-7">
      <form-field for-id="city" :label="$t('address.city')" :field="fields.city">
        <text-input id="city" :field="fields.city" v-model="entity.city"
                    @blur="blurField('city')" @update:modelValue="validateField('city')"/>
      </form-field>
    </div>
    <div class="col-md-12">
      <form-field for-id="addressLines" :label="$t('address.address_lines')" :field="fields.addressLines">
        <text-area id="addressLines" :field="fields.addressLines" v-model="entity.addressLines"
                   @blur="blurField('addressLines')" @update:modelValue="validateField('addressLines')"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {templatedForm, createField, countryCode} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextInput from '../../Library/FormInput/TextInput.vue'
import postalCodes from '../../../../resources/postal-codes.json'
import TextArea from "../../Library/FormInput/TextArea.vue";

export default {
  emits: ['update'],
  components: {
    TextArea,
    TextInput,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        addressLines: createField(),
        countryCode: createField(countryCode),
        postalCode: createField(),
        city: createField(),
      },
      entity: {
        addressLines: null,
        countryCode: null,
        postalCode: null,
        city: null,
      }
    }
  },
  watch: {
    'entity.postalCode': {
      immediate: true,
      handler: function (postalCode) {
        if (!postalCode || postalCode.length !== 4) {
          return
        }

        if (!this.fields.city.dirty) {
          const numberPostalCode = Number(postalCode)
          this.entity.city = postalCodes.find(entry => entry.pc === numberPostalCode)?.c
        }
      },
    }
  }
}

export const addressFields = ["addressLines", "countryCode", "postalCode", "city"]
</script>
