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
                    @blur="blurField('postalCode')" @update:modelValue="validateField('postalCode')"
                    @focusin="postalCodeFocus = true" @focusout="postalCodeFocus = false" @keydown="handlePostalCodeKeydown"/>
      </form-field>
    </div>
    <div class="col-md-7">
      <form-field for-id="city" :label="$t('address.city')" :field="fields.city">
        <text-input id="city" :field="fields.city" v-model="entity.city"
                    @blur="blurField('city')" @update:modelValue="validateField('city')"/>
      </form-field>
    </div>
    <div class="col-md-12">
      <span v-if="showCityHint" class="form-text d-block shift-up mb-3">
        {{ $t('_form.use_arrow_keys_to_change_city', {current:suggestedCity+1, total:matchingCities.length}) }}
      </span>
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
      postalCodeFocus: false,
      suggestedCity: 0,
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
  computed: {
    matchingCities: function () {
      if (!this.entity.postalCode || this.entity.postalCode.length !== 4) {
        return []
      }

      const numberPostalCode = Number(this.entity.postalCode)
      return postalCodes.filter(entry => entry.pc === numberPostalCode).map(c => c.c)
    },
    showCityHint: function () {
      return this.postalCodeFocus && this.matchingCities.length > 1
    }
  },
  watch: {
    matchingCities: function () {
      if (this.postalCodeFocus) {
        if (this.matchingCities.length > 0) {
          this.entity.city = this.matchingCities[0]
        }
      }
    }
  },
  methods: {
    handlePostalCodeKeydown: function (event) {
      if (!this.showCityHint) {
        return
      }

      if (event.key === 'ArrowDown') {
        this.suggestedCity = Math.min(
            this.suggestedCity + 1,
            this.matchingCities.length - 1
        )
        this.entity.city = this.matchingCities[this.suggestedCity]
      } else if (event.key === 'ArrowUp') {
        this.suggestedCity = Math.max(this.suggestedCity - 1, 0)
        this.entity.city = this.matchingCities[this.suggestedCity]
      }
    }
  }
}

export const addressFields = ["addressLines", "countryCode", "postalCode", "city"]
</script>

<style scoped>
.shift-up {
  margin-top: -0.5rem;
}
</style>
