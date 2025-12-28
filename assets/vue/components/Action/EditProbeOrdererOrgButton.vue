<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_orderer_org.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusOrdererOrg">
    <ordererOrg-form :template="template" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ServiceRequestForm from "../Form/Probe/ServiceRequestForm.vue";
import OrdererOrgForm from "../Form/Probe/OrdererOrgForm.vue";
import {probeConverter} from "../../services/domain/converters";

export default {
  emits: ['edited'],
  components: {
    OrdererOrgForm,
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
        ordererOrg: probeConverter.reconstructOrdererOrgOrganization(this.probe)
      }
    },
    payload: function () {
      if (!this.patch) {
        return null;
      }

      let payload = {...this.patch}
      if (this.patch.ordererOrg && this.patch.ordererOrg['@id']) {
        payload = {...payload, ...probeConverter.writeOrdererOrg(this.patch.ordererOrg)}
      } else{
        delete payload.ordererOrg
      }

      if (Object.keys(payload).length === 0) {
        return null
      }

      return payload
    }
  },
  methods: {
    confirm: async function () {
      await api.patch(this.probe, this.payload)

      const successMessage = this.$t('_action.edit_probe_orderer_org.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusOrdererOrg: function () {
      document.getElementById('requisitionIdentifier')?.focus()
    }
  }
}
</script>
