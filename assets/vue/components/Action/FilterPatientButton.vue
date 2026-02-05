<template>
  <button-confirm-modal
    :title="$t('_action.filter_patient.title')" icon="fas fa-filter" button-size="sm"
    :active="templateNonTrivial"
    :confirm-label="$t('_action.set_filter')" :can-confirm="payloadNonTrivial || templateNonTrivial" :confirm="confirm"
    :abort-label="$t('_action.reset_filter')" :can-abort="templateNonTrivial" :abort="reset"
    @showing="focusPatient">
    <patient-filter-form :template="template" @update="filter = $event" />
  </button-confirm-modal>
</template>

<script>

import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientFilterForm from "../Form/PatientFilterForm.vue";
import {filterAction} from "./utils/filter";

export default {
  components: {
    PatientFilterForm,
    ButtonConfirmModal,
  },
  mixins: [filterAction],
  methods: {
    focusPatient: function () {
      document.getElementById('familyName')?.focus()
    }
  }
}
</script>
