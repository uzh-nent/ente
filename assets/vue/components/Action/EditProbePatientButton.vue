<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_patient.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusPatient">
    <find-patient-form :template="template" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ServiceRequestForm from "../Form/Probe/ServiceRequestForm.vue";
import {probeConverter} from "../../services/domain/converters";
import FindPatientForm from "../Form/Probe/FindPatientForm.vue";

export default {
  emits: ['edited'],
  components: {
    FindPatientForm,
    ServiceRequestForm,
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
      return !!this.payload
    },
    template: function () {
      return {
        ...this.probe,
        patient: probeConverter.reconstructPatient(this.probe)
      }
    },
    payload: function () {
      if (this.patch?.patient && this.patch.patient['@id']) {
        return probeConverter.writePatient(this.patch.patient)
      } else{
        return null
      }
    }
  },
  methods: {
    confirm: async function () {
      await api.patch(this.probe, this.payload)

      const successMessage = this.$t('_action.edit_probe_patient.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusPatient: function () {
      document.getElementById('birthDateFilter')?.focus()
    }
  }
}
</script>
