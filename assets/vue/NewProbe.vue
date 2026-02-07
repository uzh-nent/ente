<template>
  <div class="row">
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe.service_request') }}</h3>
      <service-request-form :template="serviceRequestTemplate" @update="serviceRequest = $event"/>

      <h3 class="mt-5">{{ $t('probe.orderer') }}</h3>
      <requisition-identifier-form class="mw-20" @update="requisitionIdentifier = $event"/>
      <set-probe-orderer-org-view :can-unlink="true" @update="ordererOrg = $event"/>
      <set-probe-orderer-prac-view :can-unlink="true" @update="ordererPrac = $event"/>
    </div>
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe._name') }}</h3>
      <specimen-meta-form :specimens="specimens" :template="specimenMetaTemplate" @update="specimenMeta = $event"/>

      <template v-if="payload?.specimenSource === 'HUMAN'">
        <h3 class="mt-5">{{ $t('patient._name') }}</h3>
        <set-probe-patient-view @update="patient = $event"/>
      </template>

      <template v-if="payload?.specimenSource === 'ANIMAL'">
        <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
        <animal-name-form class="mw-20" @update="animalName = $event"/>
        <set-probe-animal-keeper-view @update="animalKeeper = $event"/>
      </template>
    </div>
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe.progress') }}</h3>
      <service-time-form :template="serviceTimeTemplate" @update="serviceTime = $event"/>
      <div class="mt-5">
        <tooltip-wrap :title="nextError">
          <button class="btn btn-primary" :disabled="!canConfirm || isConfirming" @click="confirm">
            {{ $t('_action.add_probe.title') }}
          </button>
        </tooltip-wrap>
      </div>
    </div>
  </div>
</template>

<script>

import {api, preloadApi, router} from './services/api'
import {displaySuccess} from './services/notifiers'
import LoopingRhombusSpinner from './components/Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from './components/Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ServiceRequestForm from "./components/Form/Probe/ServiceRequestForm.vue";
import moment from "moment";
import SpecimenMetaForm from "./components/Form/Probe/SpecimenMetaForm.vue";
import ServiceTimeForm from "./components/Form/Probe/ServiceTimeForm.vue";
import RequisitionIdentifierForm from "./components/Form/Probe/RequisitionIdentifierForm.vue";
import SetProbeOrdererOrgView from "./components/Action/SetProbeOrdererOrgView.vue";
import SetProbeOrdererPracView from "./components/Action/SetProbeOrdererPracView.vue";
import SetProbePatientView from "./components/Action/SetProbePatientView.vue";
import SetProbeAnimalKeeperView from "./components/Action/SetProbeAnimalKeeperView.vue";
import AnimalNameForm from "./components/Form/Probe/AnimalNameForm.vue";
import TooltipWrap from "./components/Library/View/TooltipWrap.vue";

export default {
  emits: ['added'],
  components: {
    TooltipWrap,
    AnimalNameForm,
    SetProbeAnimalKeeperView,
    SetProbePatientView,
    SetProbeOrdererPracView,
    SetProbeOrdererOrgView,
    RequisitionIdentifierForm,
    ServiceTimeForm,
    SpecimenMetaForm,
    ServiceRequestForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data() {
    return {
      specimens: undefined,

      serviceRequest: null,

      requisitionIdentifier: null,
      ordererOrg: null,
      ordererPrac: null,

      specimenMeta: null,
      patient: null,
      animalName: null,
      animalKeeper: null,

      serviceTime: null,

      isConfirming: false
    }
  },
  computed: {
    canConfirm: function () {
      return this.serviceRequest && this.specimenMeta && this.serviceTime &&
          this.requisitionIdentifier && (this.ordererOrg || this.ordererPrac) &&
          (this.payload.specimenSource !== 'HUMAN' || this.patient)
      // note: allowed to add animal without reference to animal keeper
    },
    nextError: function () {
      if (!this.requisitionIdentifier) {
        return this.$t('_form.new_probe.requisition_identifier_required')
      }

      if (!this.ordererOrg && !this.ordererPrac) {
        return this.$t('_form.new_probe.orderer_required')
      }

      if (this.payload.specimenSource === 'HUMAN' && !this.patient) {
        return this.$t('_form.new_probe.patient_required')
      }

      return null
    },
    serviceRequestTemplate: function () {
      return {
        laboratoryFunction: 'REFERENCE',
        pathogen: 'SALMONELLA',
        analysisTypes: ['IDENTIFICATION'],
        methodTypes: ['SOP'],
      }
    },
    specimenMetaTemplate: function () {
      return {
        specimenSource: 'HUMAN',
        specimenIsolate: true,
        specimen: this.specimens.find(specimen => specimen.displayName.includes("Stool"))
      }
    },
    serviceTimeTemplate: function () {
      return {
        receivedDate: moment().format('YYYY-MM-DD'),
        analysisStartDate: moment().format('YYYY-MM-DD'),
      }
    },
    payload: function () {
      let base = {}
      if (this.serviceRequest) {
        base = {...base, ...this.serviceRequestTemplate, ...this.serviceRequest}
      }

      if (this.specimenMeta) {
        base = {...base, ...this.specimenMetaTemplate, ...this.specimenMeta}
        if (base.specimen) {
          base.specimen = base.specimen['@id']
        }
      }

      if (this.serviceTime) {
        base = {...base, ...this.serviceTimeTemplate, ...this.serviceTime}
      }

      if (this.requisitionIdentifier) {
        base = {...base, ...this.requisitionIdentifier}
      }

      if (this.ordererOrg) {
        base = {...base, ...this.ordererOrg}
      }

      if (this.ordererPrac) {
        base = {...base, ...this.ordererPrac}
      }

      if (this.patient) {
        base = {...base, ...this.patient}
      }

      if (base.specimenSource === 'ANIMAL') {
        if (this.animalName) {
          base = {...base, ...this.animalName}
        }
        if (this.animalKeeper) {
          base = {...base, ...this.animalKeeper}
        }
      }

      if (base.specimenSource === 'HUMAN' && this.patient) {
        base = {...base, ...this.patient}
      }

      return base;
    }
  },
  methods: {
    confirm: async function () {
      this.isConfirming = true

      try {
        const probe = await api.postProbe(this.payload)
        this.$emit('added', probe)

        const successMessage = this.$t('_action.add_probe.added')
        displaySuccess(successMessage)

        window.location.href = router.probeActiveView(probe)
      } finally {
        this.isConfirming = false
      }
    }
  },
  beforeMount() {
    const {specimens} = preloadApi.getNewProbe()
    this.specimens = specimens
  },
  mounted() {
    this.$nextTick(() => {
      document.getElementById('requisitionIdentifier')?.focus()
    })
  }
}
</script>

<style scoped>
.mw-20 {
  max-width: 20em;
}
</style>
