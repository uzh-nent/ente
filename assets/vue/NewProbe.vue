<template>
  <div class="row">
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe.service_request') }}</h3>
      <service-request-form :template="serviceRequestTemplate" @update="serviceRequest = $event"/>

      <h3 class="mt-5">{{ $t('probe.orderer') }}</h3>
      <orderer-org-form @update="ordererOrg = $event"/>
      <!--
        <orderer-prac-form @update="ordererPrac = $event"/>
        -->
    </div>
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe._name') }}</h3>
      <specimen-meta-form :specimens="specimens" :template="specimenMetaTemplate" @update="specimenMeta = $event"/>

      <template v-if="payload?.specimenSource === 'HUMAN'">
        <h3 class="mt-5">{{ $t('patient._name') }}</h3>
        <find-patient-form @update="patient = $event"/>
      </template>

      <template v-if="payload?.specimenSource === 'ANIMAL'">
        <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
        <owner-form @update="owner = $event"/>
      </template>
    </div>
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe.progress') }}</h3>
      <service-time-form :template="serviceTimeTemplate" @update="serviceTime = $event"/>

      <button class="btn btn-primary mt-5" :disabled="!canConfirm || isConfirming" @click="confirm">
        {{ $t('_action.add_probe.title') }}
      </button>
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
import OwnerForm from "./components/Form/Probe/OwnerForm.vue";
import FindPatientForm from "./components/Form/Probe/FindPatientForm.vue";
import ServiceTimeForm from "./components/Form/Probe/ServiceTimeForm.vue";
import {probeConverter} from "./services/domain/converters";
import OrdererOrgForm from "./components/Form/Probe/OrdererOrgForm.vue";
import OrdererPracForm from "./components/Form/Probe/OrdererPracForm.vue";

export default {
  emits: ['added'],
  components: {
    OrdererPracForm,
    OrdererOrgForm,
    ServiceTimeForm,
    OwnerForm,
    FindPatientForm,
    SpecimenMetaForm,
    ServiceRequestForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data() {
    return {
      specimens: undefined,

      serviceRequest: null,
      ordererOrg: null,
      ordererPrac: null,

      specimenMeta: null,
      patient: null,
      owner: null,

      serviceTime: null,

      isConfirming: false
    }
  },
  computed: {
    canConfirm: function () {
      return this.serviceRequest && this.specimenMeta && this.serviceTime &&
          (this.ordererOrg || this.ordererPrac) &&
          (this.payload.specimenSource !== 'HUMAN' || this.patient)
      // note: allowed to add animal without reference to animal keeper
    },
    serviceRequestTemplate: function () {
      return {
        laboratoryFunction: 'REFERENCE',
        pathogen: 'SALMONELLA',
        analysisTypes: ['IDENTIFICATION'],
        receivedDate: moment().format('YYYY-MM-DD'),
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

      if (this.ordererOrg) {
        base.requisitionIdentifier = this.ordererOrg.requisitionIdentifier
        if (this.ordererOrg.ordererOrg) {
          base = {...base, ...probeConverter.writeOrdererOrg(this.ordererOrg.ordererOrg)}
        }
      }

      // TODO only show if enabled in UI
      if (this.ordererPrac) {
        base.requisitionIdentifier = this.ordererPrac.requisitionIdentifier
        if (this.ordererPrac.ordererPrac) {
          base = {...base, ...probeConverter.writeOrdererPrac(this.ordererPrac.ordererPrac)}
        }
      }

      if (base.specimenSource === 'ANIMAL' && this.owner) {
        base.animalName = this.owner.animalName
        if (this.owner.animalKeeper) {
          base = {...base, ...probeConverter.writeAnimalKeeper(this.owner.animalKeeper)}
        }
      }

      if (base.specimenSource === 'HUMAN' && this.patient) {
        base = {...base, ...probeConverter.writePatient(this.patient.patient)}
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

        router.navigateToActiveProbe(probe)
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
