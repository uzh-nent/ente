<template>
  <div class="row" v-if="probe">
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe.service_request') }}</h3>
      <actionable-view>
        <service-request-view :probe="probe"/>
        <template v-slot:actions v-if="observations.length === 0 && !probe.finishedAt">
          <edit-probe-service-request-button :probe="probe"/>
        </template>
      </actionable-view>

      <h3 class="mt-5">{{ $t('probe.orderer') }}</h3>
      <actionable-view>
        <orderer-view :probe="probe"/>
        <template v-slot:actions v-if="!probe.finishedAt">
          <edit-probe-orderer-button :probe="probe"/>
        </template>
      </actionable-view>

      <h3 class="mt-5">{{ $t('probe._name') }}</h3>
      <actionable-view>
        <specimen-meta-view :probe="probe" :specimens="specimens"/>
        <template v-slot:actions v-if="!probe.finishedAt">
          <edit-probe-specimen-meta-button :specimens="specimens" :probe="probe"/>
        </template>
      </actionable-view>

      <template v-if="probe.specimenSource === 'HUMAN'">
        <h3 class="mt-5">{{ $t('patient._name') }}</h3>
        <actionable-view>
          <patient-view :probe="probe"/>
          <template v-slot:actions v-if="!probe.finishedAt">
            <edit-probe-patient-button :probe="probe"/>
          </template>
        </actionable-view>
      </template>

      <template v-if="probe.specimenSource === 'ANIMAL'">
        <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
        <actionable-view>
          <owner-view :probe="probe"/>
          <template v-slot:actions v-if="!probe.finishedAt">
            <edit-probe-owner-button :probe="probe"/>
          </template>
        </actionable-view>
      </template>
    </div>
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe.progress') }}</h3>
      <actionable-view>
        <service-time-view :probe="probe"/>
        <template v-slot:actions v-if="!probe.finishedAt">
          <edit-probe-service-time-button :probe="probe"/>
        </template>
      </actionable-view>

      <h3 class="mt-5">{{ $t('observation._name') }}</h3>
      <add-identification-observation-button
          v-if="missingIdentificationObservation" @added="observations.push($event)"
          :probe="probe" :organisms="organisms" />
      <div class="d-flex flex-column gap-2">
        <actionable-view v-for="observation in identificationObservations" :key="observation['@id']">
          <identification-view
              :organisms="organisms" :observation="observation" />
          <template v-slot:actions v-if="!probe.finishedAt">
            <edit-identification-observation-button :probe="probe" :organisms="organisms" :observation="observation" />
          </template>
        </actionable-view>
      </div>
    </div>
  </div>
</template>

<script>

import {preloadApi} from './services/api'
import ServiceRequestView from "./components/View/Probe/ServiceRequestView.vue";
import PatientView from "./components/View/Probe/PatientView.vue";
import OrdererView from "./components/View/Probe/OrdererView.vue";
import OwnerView from "./components/View/Probe/OwnerView.vue";
import ActionableView from "./components/Library/View/ActionableView.vue";
import EditProbeServiceRequestButton from "./components/Action/EditProbeServiceRequestButton.vue";
import EditProbeOrdererButton from "./components/Action/EditProbeOrdererButton.vue";
import EditProbePatientButton from "./components/Action/EditProbePatientButton.vue";
import EditProbeOwnerButton from "./components/Action/EditProbeOwnerButton.vue";
import SpecimenMetaView from "./components/View/Probe/SpecimenMetaView.vue";
import EditProbeSpecimenMetaButton from "./components/Action/EditProbeSpecimenMetaButton.vue";
import ServiceTimeForm from "./components/Form/Probe/ServiceTimeForm.vue";
import ServiceTimeView from "./components/View/Probe/ServiceTimeView.vue";
import EditProbeServiceTimeButton from "./components/Action/EditProbeServiceTimeButton.vue";
import AddIdentificationObservationButton from "./components/Action/AddIdentificationObservationButton.vue";
import IdentificationView from "./components/View/Observation/IdentificationView.vue";
import EditIdentificationObservationButton from "./components/Action/EditIdentificationObservationButton.vue";

export default {
  emits: ['added'],
  components: {
    EditIdentificationObservationButton,
    IdentificationView,
    AddIdentificationObservationButton,
    EditProbeServiceTimeButton,
    ServiceTimeView,
    ServiceTimeForm,
    EditProbeSpecimenMetaButton,
    SpecimenMetaView,
    EditProbeOwnerButton,
    EditProbePatientButton,
    EditProbeOrdererButton,
    EditProbeServiceRequestButton,
    ActionableView,
    OwnerView,
    OrdererView,
    PatientView,
    ServiceRequestView,
  },
  data() {
    return {
      probe: undefined,

      specimens: undefined,
      leadingCodes: undefined,
      organisms: undefined,

      observations: undefined,
    }
  },
  computed: {
    missingIdentificationObservation: function () {
      return this.probe.analysisTypes.some(at => at === 'IDENTIFICATION') &&
          !this.observations.some(o => o.analysisType === 'IDENTIFICATION')
    },
    identificationObservations: function () {
      return this.observations.filter(o => o.analysisType === 'IDENTIFICATION')
    },
    missingPrimaryObservations: function () {
      return this.probe.analysisTypes.filter(at => at !== 'IDENTIFICATION' &&
          !this.observations.some(o => o.analysisType === at))
    }
  },
  mounted() {
    const {probe, specimens, leadingCodes, organisms, observations} = preloadApi.getViewActiveProbe()
    this.specimens = specimens
    this.leadingCodes = leadingCodes
    this.organisms = organisms
    this.observations = observations
    this.probe = probe
  }
}
</script>
