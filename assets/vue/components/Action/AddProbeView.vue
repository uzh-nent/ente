<template>
  <div class="row">
    <div class="col-md-4">
      <h3>{{ $t('probe.service_request') }}</h3>
      <service-request-form :template="serviceRequestTemplate" @update="serviceRequest = $event"/>

      <h3 class="mt-5">{{ $t('probe.orderer') }}</h3>
      <orderer-form @update="orderer = $event"/>
    </div>
    <template v-if="specimens">
      <div class="col-md-4">
        <h3>{{ $t('probe._name') }}</h3>
        <specimen-meta-form :specimens="specimens" :template="specimenMetaTemplate" @update="specimenMeta = $event"/>

        <template v-if="payload?.specimenSource === 'HUMAN'">
          <h3 class="mt-5">{{ $t('patient._name') }}</h3>
          <find-patient-form @update="patient = $event"/>
        </template>

        <template v-if="payload.specimenSource === 'ANIMAL'">
          <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
          <owner-form @update="owner = $event"/>
        </template>
      </div>
      <div class="col-md-4">
        <h3>{{ $t('probe.progress') }}</h3>
        <service-time-form :template="serviceTimeTemplate" @update="serviceTime = $event"/>

        <button class="btn btn-primary mt-5" :disabled="!canConfirm || isConfirming" @click="confirm">
          {{ $t('_action.add_probe.title') }}
        </button>
      </div>
    </template>
  </div>
</template>

<script>

import {api, router} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ServiceRequestForm from "../Form/Probe/ServiceRequestForm.vue";
import moment from "moment";
import OrdererForm from "../Form/Probe/OrdererForm.vue";
import SpecimenMetaForm from "../Form/Probe/SpecimenMetaForm.vue";
import OwnerForm from "../Form/Probe/OwnerForm.vue";
import FindPatientForm from "../Form/Probe/FindPatientForm.vue";
import ServiceTimeForm from "../Form/Probe/ServiceTimeForm.vue";

export default {
  emits: ['added'],
  components: {
    ServiceTimeForm,
    OwnerForm,
    FindPatientForm,
    SpecimenMetaForm,
    OrdererForm,
    ServiceRequestForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data() {
    return {
      specimens: undefined,

      serviceRequest: null,
      orderer: null,

      specimenMeta: null,
      patient: null,
      owner: null,

      serviceTime: null,

      isConfirming: false
    }
  },
  computed: {
    canConfirm: function () {
      return this.serviceRequest && this.orderer && this.specimenMeta && this.serviceTime &&
          (this.payload.specimenSource !== 'HUMAN' || this.patient) &&
          (this.payload.specimenSource !== 'ANIMAL' || this.owner)
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
    serviceTimeTemplate: function () {
      return {
        receivedAt: moment().format('YYYY-MM-DD'),
        analysisStartAt: moment().format('YYYY-MM-DD'),
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

      if (this.orderer) {
        base.ordererIdentifier = this.orderer.ordererIdentifier
        base.orderer = this.orderer.orderer['@id']
        base.ordererName = this.orderer.orderer.name
        base.ordererAddressLine = this.orderer.orderer.addressLine
        base.ordererCity = this.orderer.orderer.city
        base.ordererPostalCode = this.orderer.orderer.postalCode
        base.ordererCountryCode = this.orderer.orderer.countryCode
      }

      if (this.owner) {
        base.animalName = this.owner.animalName
        base.animalKeeper = this.owner.animalKeeper['@id']
        base.animalKeeperName = this.owner.animalKeeper.name
        base.animalKeeperAddressLine = this.owner.animalKeeper.addressLine
        base.animalKeeperCity = this.owner.animalKeeper.city
        base.animalKeeperPostalCode = this.owner.animalKeeper.postalCode
        base.animalKeeperCountryCode = this.owner.animalKeeper.countryCode
      }

      if (this.patient) {
        base.patient = this.patient.patient['@id']
        base.patientGivenName = this.patient.patient.givenName
        base.patientFamilyName = this.patient.patient.familyName
        base.patientBirthDate = this.patient.patient.birthDate
        base.patientAhvNumber = this.patient.patient.ahvNumber
        base.patientAddressLine = this.patient.patient.addressLine
        base.patientCity = this.patient.patient.city
        base.patientPostalCode = this.patient.patient.postalCode
        base.patientCountryCode = this.patient.patient.countryCode
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
      } catch (e) {
        console.log(e)
      } finally {
        this.isConfirming = false
      }
    }
  },
  mounted() {
    api.getSpecimens().then(specimens => {
      this.specimens = specimens;
    })
  }
}
</script>
