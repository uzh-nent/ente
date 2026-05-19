<template>
  <button-modal
      :title="$t('_action.view_report_email.title')" icon="fas fa-envelope" button-size="sm" color="success">
    <report-email-view :report-email="reportEmail" :users="users"/>
  </button-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import ReportEmailForm from "../Form/ReportEmailForm.vue";
import Modal from "../Library/Behaviour/Modal/Modal.vue";
import ButtonModal from "../Library/Behaviour/Modal/ButtonModal.vue";
import ReportEmailView from "../View/ReportEmailView.vue";

export default {
  emits: ['added'],
  components: {
    ReportEmailView,
    ButtonModal,
  },
  props: {
    reportEmail: {
      type: Object,
      required: true,
    },
    users: {
      type: Array,
      required: true
    },
  }
}
</script>
