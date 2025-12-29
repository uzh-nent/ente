<template>
  <button-confirm-modal
      :title="$t('_action.add_identification_observation.title')" icon="fas fa-plus"
      :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusIdentification">
    <identification-form :pathogen="probe.pathogen" :organisms="organisms" :template="template" @update="post = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import AnimalKeeperForm from "../Form/AnimalKeeperForm.vue";
import IdentificationForm from "../Form/Observation/IdentificationForm.vue";
import moment from "moment/moment";

export default {
  emits: ['added'],
  components: {
    IdentificationForm,
    AnimalKeeperForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data() {
    return {
      post: null
    }
  },
  props: {
    probe: {
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
      return !!this.post
    },
    template: function () {
      return {
        effectiveAt: moment().format(),
        identificationSuccessful: true,
      }
    },
    payload: function () {
      const payload = {...this.template, ...this.post, analysisType: 'IDENTIFICATION', probe: this.probe['@id']}

      payload.interpretation = payload.identificationSuccessful ? 'POS' : 'NEG'
      delete payload.identificationSuccessful

      if (payload.organism) {
        payload.organism = payload.organism['@id']
      }

      return payload
    }
  },
  methods: {
    confirm: async function () {
      const observation = await api.postObservation(this.payload)
      this.$emit('added', observation)

      const successMessage = this.$t('_action.add_identification_observation.added')
      displaySuccess(successMessage)
    },
    focusIdentification: function () {
      document.getElementById('identificationSuccessful')?.focus()
    }
  }
}
</script>
