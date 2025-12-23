<template>
  <div class="row">
    <div class="col-md-4">
      <h3>Auftrag</h3>
      <service-request-form :template="serviceRequestTemplate" @update="serviceRequest = $event" />
    </div>
    <div class="col-md-4">
      <h3>Probe</h3>
      <probe-meta-form :specimens="specimens" :template="probeMetaTemplate" @update="probeMeta = $event" />
    </div>
  </div>

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
import ProbeMetaForm from "../Form/ProbeMetaForm.vue";

export default {
  emits: ['added'],
  components: {
    ProbeMetaForm,
    ServiceRequestForm,
    AnimalKeeperForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data () {
    return {
      specimens: undefined,

      serviceRequest: null,
      probeMeta: null,
    }
  },
  computed: {
    canConfirm: function () {
      return !!this.serviceRequest
    },
    ordererTemplate: function () {
      return {}
    },
    serviceRequestTemplate: function () {
      return {
        laboratoryFunction: 'REFERENCE',
        pathogen: 'SALMONELLA',
        analysisTypes: ['IDENTIFICATION'],
        receivedAt: moment().format('YYYY-MM-DD'),
      }
    },
    probeMetaTemplate: function () {
      return {
        specimenSource: 'HUMAN',
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
  },
  mounted() {
    api.getSpecimens().then(specimens => {
      this.specimens = specimens;
    })
  }
}
</script>
