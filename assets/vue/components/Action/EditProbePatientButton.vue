<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_orderer.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm">
    <set-probe-patient-view :probe="probe" @update="patient = $event"/>
  </button-confirm-modal>
</template>

<script>

import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import SetProbePatientView from "./SetProbePatientView.vue";
import {api} from "../../domain/api";

export default {
  emits: ['edited'],
  components: {
    SetProbePatientView,
    ButtonConfirmModal,
  },
  data() {
    return {
      patient: null
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
      return Object.keys(this.payload).length > 0
    },
    payload: function () {
      let payload = {}
      if (this.patient) {
        payload = {...payload, ...this.patient}
      }

      return payload
    }
  },
  methods: {
    confirm: async function () {
      await api.patch(this.probe, this.payload)

      const successMessage = this.$t('_action.edit_probe_patient.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
  }
}
</script>
