<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_service_time.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusServiceTime">
    <service-time-form edit-mode :template="probe" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import ServiceTimeForm from "../Form/Probe/ServiceTimeForm.vue";

export default {
  emits: ['edited'],
  components: {
    ServiceTimeForm,
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

      const successMessage = this.$t('_action.edit_probe_service_time.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusServiceTime: function () {
      document.getElementById('receivedDate')?.focus()
    }
  }
}
</script>
