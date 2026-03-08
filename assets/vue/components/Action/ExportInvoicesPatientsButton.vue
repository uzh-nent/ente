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
import { displaySuccess } from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import PeriodForm from "../Form/PeriodForm.vue";
import moment from "moment";
import {downloadFile, excelMimeType} from "./utils/download";

export default {
  components: {
    PeriodForm,
    PatientForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data () {
    return {
      period: null
    }
  },
  props: {
    template: {
      type: Object,
      required: false
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.period
    },
    extendedTemplate: function () {
      const from = moment().startOf('month').subtract(1, 'month')
      const to = moment().startOf('month').subtract(1, 'day')
      return {
        ...this.template,
        'period[after]': from.format('YYYY-MM-DD'),
        'period[before]': to.format('YYYY-MM-DD'),
      }
    },
  },
  methods: {
    confirm: async function () {
      this.downloading = true;

      const payload = {...this.extendedTemplate, ...this.period};
      const response = await api.getInvoicesPatientsExcel(payload)
      await downloadFile(response, excelMimeType, 'invoices-patients.xlsx')

      this.downloading = false;
    },
    focusPeriod: function () {
      document.getElementById('period')?.focus()
    }
  }
}
</script>
