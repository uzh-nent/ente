<template>
  <button-confirm-modal
      :title="$t('_action.add_address.title')" icon="fas fa-plus"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusAddressSource">
    <form-field for-id="addressSource" :label="$t('report.address_source')">
      <radio id="addressSource" :choices="addressSources"
             v-model="addressSource"/>
    </form-field>

    <organization-address-form v-if="addressSource === 'ORGANIZATION'" @update="organizationAddress = $event"/>
    <practitioner-address-form v-else-if="addressSource === 'PRACTITIONER'" @update="practitionerAddress = $event"/>
    <text-area v-else id="customAddress" v-model="customAddress" />
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import FormField from "../Library/FormLayout/FormField.vue";
import Radio from "../Library/FormInput/Radio.vue";
import TextArea from "../Library/FormInput/TextArea.vue";
import OrganizationAddressForm from "../Form/Address/OrganizationAddressForm.vue";
import PractitionerAddressForm from "../Form/Address/PractitionerAddressForm.vue";

const createAddressSources = function (translator) {
  return [
    {label: translator(`organization._name`), value: 'ORGANIZATION'},
    {label: translator(`practitioner._name`), value: 'PRACTITIONER'},
    {label: translator(`report._address_source.CUSTOM`), value: 'CUSTOM'}
  ]
}

export default {
  emits: ['add'],
  components: {
    PractitionerAddressForm,
    OrganizationAddressForm,
    TextArea,
    Radio, FormField,
    PatientForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      addressSource: 'ORGANIZATION',

      organizationAddress: null,
      practitionerAddress: null,
      customAddress: null,
    }
  },
  computed: {
    canConfirm: function () {
      return !!this.address
    },
    address: function () {
      if (this.addressSource === 'ORGANIZATION') {
        return this.organizationAddress
      } else if (this.addressSource === 'PRACTITIONER') {
        return this.practitionerAddress
      } else {
        return this.customAddress
      }
    },
    addressSources: function () {
      return createAddressSources(this.$t)
    }
  },
  methods: {
    confirm: async function () {
      this.$emit('add', this.address)
    },
    focusAddressSource: function () {
      document.getElementById('addressSource')?.focus()
    }
  }
}
</script>
