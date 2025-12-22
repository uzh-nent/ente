<template>

  <div class="row">
    <div class="col-md-6">
      <form-field for-id="birthDate" :label="$t('patient.birth_date')" :field="fields.birthDate">
        <date-time-input id="birthDate" :field="fields.birthDate" v-model="entity.birthDate" format="date"
                         @blur="blurField('birthDate')" @update:modelValue="validateField('birthDate')"/>
      </form-field>
    </div>
    <div class="col-md-6">
      <form-field for-id="ahvNumber" :label="$t('patient.ahv_number')" :field="fields.ahvNumber">
        <text-input id="ahvNumber" type="text" :field="fields.ahvNumber" v-model="entity.ahvNumber"
                    @blur="blurField('ahvNumber')" @update:modelValue="validateField('ahvNumber')"/>
      </form-field>
    </div>
  </div>

  <hr/>

  <div class="row">
    <div class="col-md-12">
      <radio class="mt-2 mb-2" id="gender" :choices="genderChoices" :field="fields.gender"
             v-model="entity.gender" @update:model-value="validateField('gender')"/>
    </div>
    <div class="col-md-6">
      <form-field for-id="givenName" :label="$t('person.given_name')" :field="fields.givenName">
        <text-input id="givenName" type="text" :field="fields.givenName" v-model="entity.givenName"
                    @blur="blurField('givenName')" @update:modelValue="validateField('givenName')"/>
      </form-field>
    </div>
    <div class="col-md-6">
      <form-field for-id="familyName" :label="$t('person.family_name')" :field="fields.familyName">
        <text-input id="familyName" type="text" :field="fields.familyName" v-model="entity.familyName"
                    @blur="blurField('familyName')" @update:modelValue="validateField('familyName')"/>
      </form-field>
    </div>
  </div>

  <hr/>
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
import {templatedForm, createField, requiredRule, countryCode} from './utils/form'
import FormField from '../Library/FormLayout/FormField'
import TextInput from '../Library/FormInput/TextInput.vue'
import TextArea from '../Library/FormInput/TextArea.vue'
import DateTimeInput from '../Library/FormInput/DateTimeInput.vue'
import CustomSelect from '../Library/FormInput/CustomSelect.vue'
import postalCodes from '../../../resources/postal-codes.json'
import Radio from "../Library/FormInput/Radio.vue";

const createGenderChoices = function (translator) {
  return [
    {label: translator('patient._gender.MALE'), value: 'MALE'},
    {label: translator('patient._gender.FEMALE'), value: 'FEMALE'},
    {label: translator('patient._gender.OTHER'), value: 'OTHER'},
    {label: translator('patient._gender.UNKNOWN'), value: null},
  ]
}
export default {
  emits: ['update'],
  components: {
    Radio,
    CustomSelect,
    DateTimeInput,
    TextArea,
    TextInput,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        birthDate: createField(requiredRule),
        ahvNumber: createField(),

        gender: createField(),
        givenName: createField(),
        familyName: createField(),

        addressLines: createField(),
        countryCode: createField(countryCode),
        postalCode: createField(),
        city: createField(),
      },
      entity: {
        birthDate: null,
        ahvNumber: null,

        gender: null,
        givenName: null,
        familyName: null,

        addressLines: null,
        countryCode: null,
        postalCode: null,
        city: null,
      }
    }
  },
  computed: {
    genderChoices: function () {
      return createGenderChoices(this.$t)
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
</script>
