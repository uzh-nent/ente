<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_owner.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusOwner">
    <owner-form :template="template" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ServiceRequestForm from "../Form/Probe/ServiceRequestForm.vue";
import OwnerForm from "../Form/Probe/OwnerForm.vue";
import {probeConverter} from "../../services/domain";

export default {
  emits: ['edited'],
  components: {
    OwnerForm,
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
        animalName: this.probe.animalName,
        animalKeeper: probeConverter.reconstructAnimalKeeper(this.probe)
      }
    },
    payload: function () {
      if (!this.patch) {
        return null;
      }

      let payload = {...this.patch}
      if (this.patch.animalKeeper && this.patch.animalKeeper['@id']) {
        payload = {...payload, ...probeConverter.writeAnimalKeeper(this.patch.animalKeeper)}
      } else{
        delete payload.animalKeeper
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

      const successMessage = this.$t('_action.edit_probe_owner.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusOwner: function () {
      document.getElementById('animalName')?.focus()
    }
  }
}
</script>
