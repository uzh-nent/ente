<template>
  <button-confirm-modal
      :title="$t('_action.add_invoice.title')" icon="fas fa-plus"
      :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="load">
    <invoice-meta-form
        :template="invoiceMetaTemplate" :probe="probe"
        :is-loading-entities="entitiesLoading" :patient="patient" :organization="organization"
        :practitioner="practitioner"
        @update="invoiceMeta = $event"/>

    <hr/>

    <div class="d-flex flex-column gap-3">
      <div v-for="(lineItem, i) in lineItemTemplates" :key="i" class="mt-3 p-2 bg-light">
        <checkbox
            :id="'toggle-' + i" :label="lineItem.service"
            :model-value="shownLineItems.includes(i)" @update:modelValue="toggleShownLineItems(i, $event)"
        />
        <invoice-line-item-form class="mt-2" v-if="shownLineItems.includes(i)"
                                :template="lineItem" @update="lineItems[i] = $event"/>
      </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
      <b>{{ $t('_action.add_invoice.total') }}</b>
      <b>CHF {{ total.toFixed(2) }}</b>
    </div>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import moment from "moment";
import Checkbox from "../Library/FormInput/Checkbox.vue";
import {createLineItems} from "../../services/domain/invoice";
import InvoiceLineItemForm from "../Form/InvoiceLineItemForm.vue";
import InvoiceMetaForm from "../Form/InvoiceMetaForm.vue";
import tarif from '../../../resources/tarif.json'

export default {
  emits: ['added'],
  components: {
    InvoiceMetaForm,
    InvoiceLineItemForm,
    Checkbox,
    ButtonConfirmModal,
  },
  data() {
    return {
      invoiceMeta: null,
      lineItems: [],
      shownLineItems: [],

      entitiesLoading: true,
      patient: null,
      organization: null,
      practitioner: null,
    }
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return this.invoiceMeta && this.lineItems.length > 0 && this.shownLineItems.every(index => !!this.lineItems[index])
    },
    invoiceMetaTemplate: function () {
      return {date: moment().format('YYYY-MM-DD')}
    },
    lineItemTemplates: function () {
      return createLineItems(this.probe, tarif, this.$t)
    },
    invoicedLineItems: function () {
      return this.lineItemTemplates.map((template, index) => {
        return {
          ...template,
          ...this.lineItems[index]
        }
      }).filter((_, index) => this.shownLineItems.includes(index))
    },
    payload: function () {
      const payload = {...this.invoiceMetaTemplate, ...this.invoiceMeta, probe: this.probe['@id']}
      payload.lineItems = this.invoicedLineItems

      return payload
    },
    total: function () {
      return this.invoicedLineItems.reduce((sum, lineItem) => sum + (lineItem.tp ?? 0) * lineItem.tpw, 0)
    }
  },
  methods: {
    toggleShownLineItems: function (i, value) {
      const otherShownLineItems = this.shownLineItems.filter(o => o !== i)
      this.shownLineItems = value ? otherShownLineItems.concat(i) : otherShownLineItems
    },
    confirm: async function () {
      const invoice = await api.postInvoice(this.payload)
      this.$emit('added', invoice)

      const successMessage = this.$t('_action.add_invoice.added')
      displaySuccess(successMessage)

      await api.patch(this.probe, {invoiceStatus: 'INVOICED'})
    },
    load: async function () {
      this.$nextTick(() => {
        document.getElementById('receiver_ORDERER')?.focus()
      })

      this.entitiesLoading = true
      const [patient, organization, practitioner] = await Promise.all(
          [this.probe.patient, this.probe.ordererOrg, this.probe.ordererPrac]
              .map(e => e ? api.get(e) : Promise.resolve(null))
      )

      this.patient = patient
      this.organization = organization
      this.practitioner = practitioner
      this.entitiesLoading = false
    }
  },
  mounted() {
    // show all per default
    this.shownLineItems = this.lineItemTemplates.map((_, i) => i)
  }
}
</script>
