<template>
  <div class="row">
    <div class="col-md-4">
      <h3>{{ $t('probe.service_request') }}</h3>
      <service-request-form :template="serviceRequestTemplate" @update="serviceRequest = $event"/>

      <h3 class="mt-5">{{ $t('probe.orderer') }}</h3>
      <orderer-form @update="orderer = $event"/>
    </div>
    <div class="col-md-4" v-if="specimens">
      <h3>{{ $t('probe._name') }}</h3>
      <specimen-meta-form :specimens="specimens" :template="specimenMetaTemplate" @update="specimenMeta = $event"/>

      <template v-if="payload?.specimenSource === 'HUMAN'">
        <h3 class="mt-5">{{ $t('patient._name') }}</h3>
      </template>

      <template v-if="payload.specimenSource === 'ANIMAL'">
        <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
        <owner-form @update="owner = $event"/>
      </template>
    </div>
  </div>

  {{ serviceRequest }}
  {{ orderer }}
  {{ specimenMeta }}
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ServiceRequestForm from "../Form/Probe/ServiceRequestForm.vue";
import moment from "moment";
import OrdererForm from "../Form/Probe/OrdererForm.vue";
import SpecimenMetaForm from "../Form/Probe/SpecimenMetaForm.vue";
import OwnerForm from "../Form/Probe/OwnerForm.vue";

export default {
  emits: ['added'],
  components: {
    OwnerForm,
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
      owner: null,
      patient: null,
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
    specimenMetaTemplate: function () {
      return {
        specimenSource: 'HUMAN',
      }
    },
    payload: function () {
      const base = {
        ...this.serviceRequestTemplate, ...this.serviceRequest,
        ...this.specimenMetaTemplate, ...this.specimenMeta,
        animalKeeper: this.owner?.animalKeeper['@id'],
      }

      if (this.orderer) {
        base.ordererIdentifier = this.orderer.ordererIdentifier
        base.orderer = this.orderer['@id']
        base.ordererName = this.owner.orderer.name
        base.ordererAddressLine = this.owner.orderer.addressLine
        base.ordererCity = this.owner.orderer.city
        base.ordererPostalCode = this.owner.orderer.postalCode
        base.ordererCountryCode = this.owner.orderer.countryCode
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
        base.patient = this.owner.patient['@id']
        base.patientGivenName = this.owner.patient.givenName
        base.patientFamilyName = this.owner.patient.familyName
        base.patientBirthDate = this.owner.patient.birthDate
        base.patientAhvNumber = this.owner.patient.ahvNumber
        base.patientAddressLine = this.owner.patient.addressLine
        base.patientCity = this.owner.patient.city
        base.patientPostalCode = this.owner.patient.postalCode
        base.patientCountryCode = this.owner.patient.countryCode
      }

      return base;
    }
  },
  methods: {
    confirm: async function () {
      const probePayload = {}
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
