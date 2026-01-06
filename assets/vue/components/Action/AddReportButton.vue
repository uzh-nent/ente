<template>
  <button-confirm-modal
      :title="$t('_action.add_report.title')" icon="fas fa-plus"
      :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusObservation">
    <report-meta-form :template="reportMetaTemplate" :probe="probe" @update="reportMeta = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ElmReportForm from "../Form/ElmReportForm.vue";
import ReportMetaForm from "../Form/ReportMetaForm.vue";
import moment from "moment";

export default {
  emits: ['added'],
  components: {
    ReportMetaForm,
    ElmReportForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      reportMeta: null,
      addresses: [],
      results: []
    }
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
    observations: {
      type: Array,
      required: true
    },
    reports: {
      type: Array,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.reportMeta
    },
    reportMetaTemplate: function () {
      const template = {date: moment().format('YYYY-MM-DD'), certified: true}
      if (this.probe.analysisTypes.length > this.observations.length) {
        return {...template, predefinedTitle: 'INTERMEDIATE'}
      } else if (this.reports.length === 0) {
        return {...template, predefinedTitle: 'FINAL'}
      } else {
        return {...template, predefinedTitle: 'ADDENDUM'}
      }
    },
    payload: function () {
      const payload = {...this.reportMetaTemplate, ...this.reportMeta, probe: this.probe['@id']}

      // normalize title
      if (payload.predefinedTitle) {
        payload.title = this.$t('report._predefined_title.' + payload.predefinedTitle)
      } else {
        payload.title = payload.customTitle
      }
      delete payload.predefinedTitle
      delete payload.customTitle

      return payload
    }
  },
  methods: {
    confirm: async function () {
      const report = await api.postReport(this.payload)
      this.$emit('added', report)

      const successMessage = this.$t('_action.add_report.added')
      displaySuccess(successMessage)
    },
    focusObservation: function () {
      document.getElementById('addAddressButton')?.focus()
    }
  },
  mounted() {
    this.addresses = this.reports.length ? this.reports[this.reports.length - 1].addresses : []
  }
}
</script>
