<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_orderer_prac.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusOrdererPrac">
    <orderer-prac-form :template="template" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ServiceRequestForm from "../Form/Probe/ServiceRequestForm.vue";
import {probeConverter} from "../../services/domain/converters";
import OrdererPracForm from "../Form/Probe/OrdererPracForm.vue";

export default {
  emits: ['edited'],
  components: {
    OrdererPracForm,
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
        ordererPrac: probeConverter.reconstructOrdererPracPractitioner(this.probe)
      }
    },
    payload: function () {
      if (!this.patch) {
        return null;
      }

      let payload = {...this.patch}
      if (this.patch.ordererPrac && this.patch.ordererPrac['@id']) {
        payload = {...payload, ...probeConverter.writeOrdererPrac(this.patch.ordererPrac)}
      } else{
        delete payload.ordererPrac
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

      const successMessage = this.$t('_action.edit_probe_orderer_prac.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusOrdererPrac: function () {
      document.getElementById('requisitionIdentifier')?.focus()
    }
  }
}
</script>
