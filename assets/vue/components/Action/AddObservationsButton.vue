<template>
  <button-confirm-modal
      :title="$t('_action.add_observations.title')" icon="fas fa-plus"
      :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusIdentification">
    <p v-if="missingAnalysisTypes.length === 0">
      {{ $t('_action.add_observations.no_missing_observations') }}
    </p>
    <template v-else>
      <test-shared-form :template="sharedTemplate" @update="sharedPost = $event"/>

      <template v-if="missingAnalysisTypes.length === 1">
        <h3 class="mt-3">{{ $t('probe._analysis_type.' + missingAnalysisTypes[0]) }}</h3>
        <identification-form
            v-if="missingAnalysisTypes[0] === 'IDENTIFICATION'" :pathogen="probe.pathogen" :organisms="organisms"
            :template="identificationTemplate" @update="updateIdentificationObservation($event)"/>
        <test-form
            v-else :id="missingAnalysisTypes[0]" :template="testTemplate"
            @update="updateTestObservation(missingAnalysisTypes[0], $event)"/>
      </template>
      <template v-else>
        <div v-for="analysisType in missingAnalysisTypes" :key="analysisType" class="mt-3 p-2 bg-light">
          <checkbox
              :id="'toggle-' + analysisType" :label="$t('probe._analysis_type.' + analysisType)"
              :model-value="observationEnabled(analysisType)"
              @update:modelValue="toggleObservation(analysisType, $event)"
          />
          <div class="mt-3" v-if="observationEnabled(analysisType)">
            <identification-form
                v-if="analysisType === 'IDENTIFICATION'" :pathogen="probe.pathogen" :organisms="organisms"
                :template="identificationTemplate" @update="updateIdentificationObservation($event)"/>
            <test-form
                v-else :id="analysisType" :template="testTemplate"
                @update="updateTestObservation(analysisType, $event)"/>
          </div>
        </div>
      </template>
    </template>
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
import TestSharedForm from "../Form/Observation/TestSharedForm.vue";
import TestForm from "../Form/Observation/TestForm.vue";
import Checkbox from "../Library/FormInput/Checkbox.vue";

export default {
  emits: ['added'],
  components: {
    Checkbox,
    TestForm,
    TestSharedForm,
    IdentificationForm,
    AnimalKeeperForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data() {
    return {
      observations: [],

      sharedPost: null
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
    missingAnalysisTypes: {
      type: Array,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.sharedPost && this.observations.length > 0
    },
    sharedTemplate: function () {
      return {
        pathogen: this.probe.pathogen,
        pathogenName: this.probe.pathogenName,
        effectiveAt: moment().format(),
      }
    },
    identificationTemplate: function () {
      return {
        identificationSuccessful: true
      }
    },
    testTemplate: function () {
      return {
        interpretation: 'NEG'
      }
    }
  },
  methods: {
    observationEnabled: function (analysisType) {
      return !!this.observations.find(o => o.analysisType === analysisType)
    },
    toggleObservation: function (analysisType, value) {
      const otherObservations = this.observations.filter(o => o.analysisType !== analysisType)
      const newObservation = {analysisType: analysisType}
      this.observations = value ? otherObservations.concat(newObservation) : otherObservations
    },
    updateIdentificationObservation: function (identificationObservation) {
      const observation = {...this.identificationTemplate, ...identificationObservation}

      observation.interpretation = observation.identificationSuccessful ? 'POS' : 'NEG'
      delete observation.identificationSuccessful

      if (observation.organism) {
        observation.organism = observation.organism['@id']
      }

      this.updateObservation('IDENTIFICATION', observation)
    },
    updateTestObservation: function (analysisType, testObservation) {
      const observation = {...this.testTemplate, ...testObservation}
      this.updateObservation(analysisType, observation)
    },
    updateObservation: function (analysisType, observation) {
      const otherObservations = this.observations.filter(o => o.analysisType !== analysisType)
      const newObservation = {...observation, analysisType: analysisType}
      this.observations = otherObservations.concat(newObservation)
    },
    confirm: async function () {
      const sharedPayload = {...this.sharedTemplate, ...this.sharedPost, probe: this.probe['@id']}
      const observationPromises = this.observations.map(o => {
        const payload = {...sharedPayload, ...o}
        return api.postObservation(payload)
      })

      const observations = await Promise.all(observationPromises)
      observations.map(o => this.$emit('added', o))
      this.observations = []

      const successMessage = this.$t('_action.add_observations.added')
      displaySuccess(successMessage)
    },
    focusIdentification: function () {
      if (this.missingAnalysisTypes.length > 0) {
        document.getElementById('toggle-' + this.missingAnalysisTypes[0])?.focus()
      }

      if (this.missingAnalysisTypes.length === 1) {
        this.toggleObservation(this.missingAnalysisTypes[0], true)
      }
    }
  }
}
</script>
