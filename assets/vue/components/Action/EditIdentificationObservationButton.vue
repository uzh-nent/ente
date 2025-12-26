<template>
  <button-confirm-modal
      :title="$t('_action.edit_identification_observation.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusIdentification">
    <identification-form :organisms="organisms" :pathogen="probe.pathogen" :template="template" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import IdentificationForm from "../Form/Observation/IdentificationForm.vue";

export default {
  emits: ['edited'],
  components: {
    IdentificationForm,
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
    observation: {
      type: Object,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.patch
    },
    template: function () {
      return {
        ...this.observation,
        organism: this.organisms.find(s => s['@id'] === this.observation.organism),
        identificationSuccessful: this.observation.interpretation === 'POS',
      }
    },
    payload: function () {
      const payload = {...this.patch}

      if (Object.prototype.hasOwnProperty.call(payload, 'identificationSuccessful')) {
        payload.interpretation = payload.identificationSuccessful ? 'POS' : 'NEG'
        delete payload.identificationSuccessful
      }

      if (payload.organism) {
        payload.organism = payload.organism['@id']
      }

      return payload
    }
  },
  methods: {
    confirm: async function () {
      await api.patch(this.observation, this.payload)

      const successMessage = this.$t('_action.edit_identification_observation.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusIdentification: function () {
      document.getElementById('searchOrganism')?.focus()
    }
  },
}
</script>
