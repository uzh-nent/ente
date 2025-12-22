<template>
  <form-field for-id="name" :label="$t('organization.name')" :field="fields.name">
    <text-input id="name" type="text" :field="fields.name" v-model="entity.name"
                @blur="blurField('name')" @update:modelValue="validateField('name')"/>
  </form-field>

  <hr/>
  <div class="row">
    <div class="col-md-2">
      <form-field for-id="countryCode" :label="$t('meeting.country_code')" :field="fields.countryCode">
        <text-input id="countryCode" type="text" :field="fields.countryCode" v-model="entity.countryCode"
                    @blur="blurField('countryCode')" @update:modelValue="validateField('countryCode')"/>
      </form-field>
    </div>
    <div class="col-md-3">
      <form-field for-id="postalCode" :label="$t('meeting.postal_code')" :field="fields.postalCode">
        <text-input id="postalCode" type="number" :field="fields.postalCode" v-model="entity.postalCode"
                    @blur="blurField('postalCode')" @update:modelValue="validateField('postalCode')"/>
      </form-field>
    </div>
    <div class="col-md-7">
      <form-field for-id="city" :label="$t('meeting.city')" :field="fields.city">
        <text-input id="city" :field="fields.city" v-model="entity.city"
                    @blur="blurField('city')" @update:modelValue="validateField('city')"/>
      </form-field>
    </div>
    <div class="col-md-12">
      <form-field for-id="addressLines" :label="$t('meeting.address_lines')" :field="fields.addressLines">
        <text-area id="addressLines" :field="fields.addressLines" v-model="entity.addressLines"
                   @blur="blurField('addressLines')" @update:modelValue="validateField('addressLines')"/>
      </form-field>
    </div>
  </div>

  <hr/>
  <form-field for-id="contact" :label="$t('meeting.contact')" :field="fields.contact">
    <text-area id="contact" :field="fields.contact" v-model="entity.contact"
               @blur="blurField('contact')" @update:modelValue="validateField('contact')"/>
  </form-field>
</template>

<script>
import {templatedForm, createField, requiredRule, countryCode} from './utils/form'
import FormField from '../Library/FormLayout/FormField'
import TextInput from '../Library/FormInput/TextInput.vue'
import TextArea from '../Library/FormInput/TextArea.vue'
import DateTimeInput from '../Library/FormInput/DateTimeInput.vue'
import CustomSelect from '../Library/FormInput/CustomSelect.vue'
import postalCodes from '../../../resources/postal-codes.json'

export default {
  emits: ['update'],
  components: {
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
        name: createField(requiredRule),

        addressLines: createField(),
        countryCode: createField(countryCode),
        postalCode: createField(),
        city: createField(),

        contact: createField()
      },
      entity: {
        name: null,

        addressLines: null,
        countryCode: null,
        postalCode: null,
        city: null,

        contact: null,
      }
    }
  },
  watch: {
    postalCode: {
      immediate: true,
      handler: function (postalCode) {
        if (!postalCode || postalCode.length !== 4 || this.city) {
          return
        }

        if (!this.fields.city.dirty) {
          this.entity.city = postalCodes.find(entry => entry.pc === postalCode)?.city
        }
      },
    }
  }
}
</script>
