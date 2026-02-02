<template>
  <button-confirm-modal
      :title="$t('_action.edit_patient.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusPatient">
    <patient-form :template="patient" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";

export default {
  emits: ['edited'],
  components: {
    PatientForm,
    ButtonConfirmModal,
  },
  data() {
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
      return this.patch && Object.keys(this.patch).length > 0
    },
  },
  methods: {
    confirm: async function () {
      const payload = {...this.patch}
      await api.patch(this.patient, payload)

      const successMessage = this.$t('_action.edit_patient.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusPatient: function () {
      document.getElementById('givenName')?.focus()
    }
  }
}
</script>
