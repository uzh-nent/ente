<template>
  <div class="row">
    <div class="col-md-6">
      <form-field for-id="date" :label="$t('invoice.date')" :field="fields.date">
        <date-time-input id="date" :field="fields.date" v-model="entity.date" format="date"
                         @blur="blurField('date')" @update:modelValue="validateField('date')"/>
      </form-field>
    </div>
    <form-field for-id="receiver" :label="$t('invoice.receiver')"
                :field="fields.receiver">
      <radio id="receiver" :choices="receivers" :field="fields.receiver"
             v-model="entity.receiver" @update:model-value="validateField('receiver')"/>
    </form-field>
    <form-field for-id="address" :label="$t('invoice.address')" v-if="entity.receiver"
                :field="fields.address">
      <text-area id="address" :field="fields.address"  :disabled="isLoadingEntities"
             v-model="entity.address" @update:model-value="validateField('address')"/>
      <p class="form-text">{{ $t('_form.invoice.use_of_invoice_address_if_defined') }}</p>
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from './utils/form'
import FormField from '../Library/FormLayout/FormField'
import TextInput from '../Library/FormInput/TextInput.vue'
import DateTimeInput from '../Library/FormInput/DateTimeInput.vue'
import Radio from "../Library/FormInput/Radio.vue";
import {probeConverter} from "../../services/domain/converters";
import {
  formatOrganizationAddress,
  formatPatientAddress,
  formatPractitionerAddress
} from "../../services/domain/formatter";
import TextArea from "../Library/FormInput/TextArea.vue";

const createReceiver = function (translator) {
  const values = ['ORDERER', 'PATIENT']
  return values.map(value => ({label: translator(`invoice._receiver.${value}`), value}))
}

export default {
  emits: ['update'],
  components: {
    TextArea,
    Radio,
    DateTimeInput,
    TextInput,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        date: createField(requiredRule),
        receiver: createField(requiredRule),
        address: createField(requiredRule),
      },
      entity: {
        date: null,
        receiver: null,
        address: null,
      },
    }
  },
  props: {
    probe: {
      type: Object,
      required: false
    },
    patient: {
      type: Object,
      required: false
    },
    organization: {
      type: Object,
      required: false
    },
    practitioner: {
      type: Object,
      required: false
    },
    isLoadingEntities: {
      type: Boolean,
      required: false
    },
  },
  computed: {
    receivers: function () {
      return createReceiver(this.$t)
    },
  },
  methods: {
    setDefaultAddress: function () {
      if (this.isLoadingEntities || !this.entity.receiver) {
        return
      }

      if (this.entity.receiver === 'PATIENT') {
        const patient = this.patient ?? probeConverter.reconstructPatient(this.probe)
        this.entity.address = patient.invoiceAddress ?? formatPatientAddress(patient)
      } else if (this.entity.receiver === 'ORDERER') {
        const organization = this.organization ?? probeConverter.reconstructOrdererOrg(this.probe)
        const practitioner = this.practitioner ?? probeConverter.reconstructOrdererPrac(this.probe)
        this.entity.address = organization ?
            (organization.invoiceAddress ?? formatOrganizationAddress(organization)) :
            (practitioner.invoiceAddress ?? formatPractitionerAddress(practitioner))
      }
      // no case for animal keeper, as not needed so far
    }
  },
  watch: {
    'entity.receiver': function () { this.setDefaultAddress() },
    isLoadingEntities: function () { this.setDefaultAddress() }
  }
}
</script>
