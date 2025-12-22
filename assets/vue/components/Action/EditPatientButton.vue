<template>
  <button-confirm-modal
    :title="$t('_action.edit_patient.title')" icon="fas fa-pencil"
    :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm">
    <div class="d-flex flex-column align-items-end">
      <div class="w-100">
        <patient-form :template="patient" @update="patch = $event" />
      </div>
    </div>
  </button-confirm-modal>
</template>

<script>

import { api } from '../../services/api'
import { displaySuccess } from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";

export default {
  components: {
    PatientForm,
    ButtonConfirmModal,
  },
  data () {
    return {
      patch: null,
    }
  },
  props: {
    patient: {
      type: Object,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.patch
    },
  },
  methods: {
    confirm: async function () {
      const payload = { ...this.patch, organizers: this.organizers }
      await api.patch(this.patient, payload)

      const successMessage = this.$t('_action.edit_patient.edited')
      displaySuccess(successMessage)
    }
  }
}
</script>
