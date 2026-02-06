<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_orderer.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusOrderer">
    <requisition-identifier-form class="mw-20" :template="probe" @update="requisitionIdentifier = $event"/>
    <set-probe-orderer-org-view :probe="probe" @update="ordererOrg = $event"/>
    <set-probe-orderer-prac-view :probe="probe" @update="ordererPrac = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ServiceRequestForm from "../Form/Probe/ServiceRequestForm.vue";
import RequisitionIdentifierForm from "../Form/Probe/RequisitionIdentifierForm.vue";
import SetProbeOrdererOrgView from "./SetProbeOrdererOrgView.vue";
import SetProbeOrdererPracView from "./SetProbeOrdererPracView.vue";

export default {
  emits: ['edited'],
  components: {
    SetProbeOrdererPracView,
    SetProbeOrdererOrgView,
    RequisitionIdentifierForm,
    ServiceRequestForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      requisitionIdentifier: null,
      ordererOrg: null,
      ordererPrac: null
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
      return !!this.requisitionIdentifier || !!this.ordererOrg || !!this.ordererPrac
    },
    payload: function () {
      let payload = {}
      if (this.requisitionIdentifier) {
        payload = {...payload, ...this.requisitionIdentifier}
      }
      if (this.ordererOrg) {
        payload = {...payload, ...this.ordererOrg}
      }
      if (this.ordererPrac) {
        payload = {...payload, ...this.ordererPrac}
      }

      return payload
    }
  },
  methods: {
    confirm: async function () {
      await api.patch(this.probe, this.payload)

      const successMessage = this.$t('_action.edit_probe_orderer.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusOrderer: function () {
      document.getElementById('requisitionIdentifier')?.focus()
    }
  }
}
</script>
