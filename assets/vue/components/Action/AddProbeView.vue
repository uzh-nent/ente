<template>
  <div class="row">
    <div class="col-md-4">
      <h3>Auftrag</h3>
      <service-request-form :template="serviceRequestTemplate" @update="serviceRequest = $event" />
      <h3 class="mt-5">Auftraggeber</h3>
      <orderer-form :template="ordererTemplate" @update="orderer = $event" />
    </div>
    <div class="col-md-4" v-if="specimens">
      <h3>Probe</h3>
      <specimen-meta-form :specimens="specimens" :template="specimenMetaTemplate" @update="specimenMeta = $event" />
    </div>
  </div>

  {{ serviceRequest }}
  {{ orderer }}
  {{ specimenMeta }}
</template>

<script>

import { api } from '../../services/api'
import { displaySuccess } from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import AnimalKeeperForm from "../Form/AnimalKeeperForm.vue";
import ServiceRequestForm from "../Form/Probe/ServiceRequestForm.vue";
import moment from "moment";
import OrdererForm from "../Form/Probe/OrdererForm.vue";
import SpecimenMetaForm from "../Form/Probe/SpecimenMetaForm.vue";

export default {
  emits: ['added'],
  components: {
    SpecimenMetaForm,
    OrdererForm,
    ServiceRequestForm,
    AnimalKeeperForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data () {
    return {
      specimens: undefined,

      orderer: null,
      serviceRequest: null,
      specimenMeta: null,
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
    specimenMetaTemplate: function () {
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
