<template>
  <button-confirm-modal
      :title="$t('_action.add_identification_observation.title')" icon="fas fa-plus"
      :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusIdentification">
    <identification-form :pathogen="pathogen" :organisms="organisms" :template="template" @update="post = $event"/>
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
    pathogen: {
      type: String,
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
        analysisStopAt: moment().format('YYYY-MM-DD'),
        identificationSuccessful: true,
      }
    },
  },
  methods: {
    confirm: async function () {
      const payload = {...this.template, ...this.post}
      payload.interpretation = payload.interpretationSuccessful ? 'POS' : 'NEG'
      delete payload.interpretationSuccessful

      const observation = await api.postObservation(payload)
      this.$emit('added', observation)

      const successMessage = this.$t('_action.add_identification_observation.added')
      displaySuccess(successMessage)
    },
    focusIdentification: function () {
      document.getElementById('searchOrganism')?.focus()
    }
  }
}
</script>
