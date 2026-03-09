<template>
  <button-confirm-modal
    :title="$t('_action.export_patient_invoices.title')" icon="fas fa-download"
    :confirm-label="$t('_action.download')" :can-confirm="canConfirm" :confirm="confirm"
    @showing="focusPeriod">
    <period-form :template="extendedTemplate" @update="period = $event" />
  </button-confirm-modal>
</template>

<script>

import { api } from '../../services/api'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import PeriodForm from "../Form/PeriodForm.vue";
import {downloadFile, excelMimeType} from "./utils/download";
import {periodExport} from "./utils/periodExport";

export default {
  components: {
    PeriodForm,
    PatientForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  mixins: [periodExport],
  methods: {
    confirm: async function () {
      const response = await api.getInvoicesPatientsExcel(this.payload)
      await downloadFile(response, excelMimeType, 'invoices-patients.xlsx')
    },
  }
}
</script>
