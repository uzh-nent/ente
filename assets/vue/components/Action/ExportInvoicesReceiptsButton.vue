<template>
  <button-confirm-modal
      :title="$t('_action.export_invoice_receipts.title')" icon="fas fa-download"
      :confirm-label="$t('_action.download')" :can-confirm="setIdentifiersSuccessful" :confirm="confirm"
      @showing="init">
    <invoice-identifiers-form @update="identifiers = $event" :disabled="setIdentifiersSuccessful || setIdentifiersLoading" />
    <button class="btn btn-primary" @click="setIdentifiers" :disabled="setIdentifiersLoading || identifiers === null"
            v-if="!setIdentifiersSuccessful">
      {{ $t("_action.export_invoice_receipts.set_identifiers") }}
    </button>
    <p class="alert alert-success" v-if="setIdentifiersSuccessful">
      {{ $tc("_action.export_invoice_receipts.set_identifiers_successful", {count: setIdentifiersResponse.successful}) }}
    </p>
    <p class="alert alert-warning mt-2" v-if="setIdentifiersResponse && !setIdentifiersSuccessful">
      {{ $t("_action.export_invoice_receipts.set_identifiers_errored") }}
      <span class="d-block p-2 bg-light mt-2" v-for="error in setIdentifiersResponse.errors"
            :key="error">{{ error }}<br></span>
    </p>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import PeriodForm from "../Form/PeriodForm.vue";
import {downloadFile, excelMimeType, pdfMimeType} from "./utils/download";
import InvoiceIdentifiersForm from "../Form/InvoiceIdentifiersForm.vue";

export default {
  components: {
    InvoiceIdentifiersForm,
    PeriodForm,
    PatientForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },

  data() {
    return {
      identifiers: null,
      loading: false,

      setIdentifiersLoading: false,
      setIdentifiersResponse: null,
    }
  },
  computed: {
    setIdentifiersSuccessful: function () {
      return this.setIdentifiersResponse && this.setIdentifiersResponse.errors.length === 0 && this.setIdentifiersResponse.successful > 0
    },
  },
  methods: {
    setIdentifiers: async function () {
      this.setIdentifiersLoading = true
      this.setIdentifiersResponse = await api.postSetIdentifiers(this.identifiers)
      this.setIdentifiersLoading = false
    },
    confirm: async function () {
      const response = await api.postDownloadReceipts(this.identifiers)
      await downloadFile(response, pdfMimeType, 'receipts.pdf')
    },
    init: function () {
      document.getElementById('probeIdentifiers')?.focus()

      this.setIdentifiersResponse = null
      this.setIdentifiersLoading = false
    },
  }
}
</script>
