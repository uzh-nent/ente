<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_service_request.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusServiceRequest">
    <service-request-form :template="probe" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import ServiceRequestForm from "../Form/Probe/ServiceRequestForm.vue";

export default {
  emits: ['edited'],
  components: {
    ServiceRequestForm,
    PatientForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      patch: null,
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
      return !!this.patch
    },
  },
  methods: {
    confirm: async function () {
      const payload = {...this.patch}
      await api.patch(this.probe, payload)

      const successMessage = this.$t('_action.edit_probe_service_request.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusServiceRequest: function () {
      document.getElementById('pathogen')?.focus()
    }
  }
}
</script>
