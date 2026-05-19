<template>
  <button-confirm-modal
    :title="$t('_action.send_report_email.title')" icon="fas fa-envelope" button-size="sm"
    :confirm-label="$t('_action.send')" :can-confirm="canConfirm" :confirm="confirm"
    @showing="focusTo">
    <report-email-form :probe="probe" :report="report" @update="post = $event" />
  </button-confirm-modal>
</template>

<script>

import { api } from '../../services/api'
import { displaySuccess } from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import ReportEmailForm from "../Form/ReportEmailForm.vue";

export default {
  emits: ['added'],
  components: {
    ReportEmailForm,
    PatientForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data () {
    return {
      post: null
    }
  },
  props: {
    probe: {
      type: Object,
      required: true,
    },
    report: {
      type: Object,
      required: true,
    }
  },
  computed: {
    canConfirm: function () {
      return !!this.post
    },
  },
  methods: {
    confirm: async function () {
      const payload = { ...this.post, probe: this.probe['@id'], report: this.report['@id'] }
      const reportEmail = await api.postReportEmail(payload)
      this.$emit('added', reportEmail)

      const successMessage = this.$t('_action.send_report_email.sent')
      displaySuccess(successMessage)
    },
    focusTo: function () {
      document.getElementById('to')?.focus()
    }
  }
}
</script>
