<template>
  <service-request-form :template="serviceRequestTemplate" @update="serviceRequest = $event" />
  {{serviceRequest}}
</template>

<script>

import { api } from '../../services/api'
import { displaySuccess } from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import AnimalKeeperForm from "../Form/AnimalKeeperForm.vue";
import ServiceRequestForm from "../Form/ServiceRequestForm.vue";
import moment from "moment";

export default {
  emits: ['added'],
  components: {
    ServiceRequestForm,
    AnimalKeeperForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data () {
    return {
      serviceRequest: null
    }
  },
  computed: {
    canConfirm: function () {
      return !!this.serviceRequest
    },
    serviceRequestTemplate: function () {
      return {
        laboratoryFunction: 'REFERENCE',
        pathogen: 'SALMONELLA',
        analysisTypes: ['IDENTIFICATION'],
        receivedAt: moment().format('YYYY-MM-DD'),
      }
    },
  },
  methods: {
    confirm: async function () {
      const probePayload = { ...this.serviceRequestTemplate, ...this.serviceRequest }
      const analysisTypes = probePayload.analysisTypes
      delete probePayload.analysisTypes
      const probe = await api.postProbe(probePayload)
      const analysisPromises = analysisTypes.map(analysisType => {
        const observation = {
          probe: probe['@id'],
          analysisType: analysisType,
        }
        return api.postObservation(observation)
      })
      await Promise.all(analysisPromises)
      this.$emit('added', probe)

      const successMessage = this.$t('_action.add_probe.added')
      displaySuccess(successMessage)
    }
  }
}
</script>
