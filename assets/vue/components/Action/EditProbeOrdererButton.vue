<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_orderer.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusOrderer">
    <orderer-form :template="template" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import ServiceRequestForm from "../Form/Probe/ServiceRequestForm.vue";
import OrdererForm from "../Form/Probe/OrdererForm.vue";
import {probeConverter} from "../../services/domain";

export default {
  emits: ['edited'],
  components: {
    OrdererForm,
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
    template: function () {
      return {
        ordererIdentifier: this.probe.ordererIdentifier,
        orderer: probeConverter.reconstructOrdererOrganization(this.probe)
      }
    },
    payload: function () {
      let payload = {...this.patch}
      if (this.patch.orderer) {
        payload = {...payload, ...probeConverter.writeOrderer(this.patch.orderer)}
      }

      return payload
    }
  },
  methods: {
    confirm: async function () {
      await api.patch(this.probe, this.payload)

      const successMessage = this.$t('_action.edit_probe_service_request.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusOrderer: function () {
      document.getElementById('ordererIdentifier')?.focus()
    }
  }
}
</script>
