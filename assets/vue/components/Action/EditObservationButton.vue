<template>
  <button-confirm-modal
      :title="$t('_action.edit_observation.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusInterpretation">
    <test-shared-form :template="sharedTemplate" @update="sharedPatch = $event"/>
    <identification-form
        v-if="observation.analysisType === 'IDENTIFICATION'"
        :organisms="organisms" :pathogen="probe.pathogen" :template="identificationTemplate"
        @update="identificationPatch = $event"/>
    <test-form
        v-else
        :id="observation.analysisType" :template="observation" @update="testPatch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import TestForm from "../Form/Observation/TestForm.vue";
import TestSharedForm from "../Form/Observation/TestSharedForm.vue";
import moment from "moment";
import IdentificationForm from "../Form/Observation/IdentificationForm.vue";

export default {
  emits: ['edited'],
  components: {
    IdentificationForm,
    TestSharedForm,
    TestForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      sharedPatch: null,
      testPatch: null,
      identificationPatch: null,
    }
  },
  props: {
    observation: {
      type: Object,
      required: true
    },
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
    sharedTemplate: function () {
      return {
        effectiveAt: moment().format()
      }
    },
    identificationTemplate: function () {
      if (this.observation.analysisType !== 'IDENTIFICATION') {
        return null
      }

      return {
        ...this.observation,
        organism: this.organisms.find(s => s['@id'] === this.observation.organism),
        identificationSuccessful: this.observation.interpretation === 'POS',
      }
    },
    canConfirm: function () {
      return !!(this.sharedPatch || this.identificationPatch || this.testPatch)
    },
    payload: function () {
      if (this.observation.analysisType === 'IDENTIFICATION') {
        const payload = {...this.sharedTemplate, ...this.sharedPatch, ...this.identificationPatch}

        if (Object.prototype.hasOwnProperty.call(payload, 'identificationSuccessful')) {
          payload.interpretation = payload.identificationSuccessful ? 'POS' : 'NEG'
          delete payload.identificationSuccessful
        }

        if (payload.organism) {
          payload.organism = payload.organism['@id']
        }

        return payload
      } else {
        return {...this.sharedTemplate, ...this.sharedPatch, ...this.testPatch}
      }
    }
  },
  methods: {
    confirm: async function () {
      await api.patch(this.observation, this.payload)

      const successMessage = this.$t('_action.edit_observation.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusInterpretation: function () {
      if (this.observation.analysisType === 'IDENTIFICATION') {
        document.getElementById('searchOrganism')?.focus()
      } else {
        document.getElementById('interpretation-' + this.observation.analysisType)?.focus()
      }
    }
  },
}
</script>
