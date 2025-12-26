<template>
  <div class="row" v-if="probe">
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe.service_request') }}</h3>
      <actionable-view>
        <service-request-view :probe="probe"/>
        <template v-slot:actions>
          <edit-probe-service-request-button :probe="probe"/>
        </template>
      </actionable-view>

      <h3 class="mt-5">{{ $t('probe.orderer') }}</h3>
      <actionable-view>
        <orderer-view :probe="probe"/>
        <template v-slot:actions>
          <edit-probe-orderer-button :probe="probe"/>
        </template>
      </actionable-view>

      <h3 class="mt-5">{{ $t('probe._name') }}</h3>
      <actionable-view>
        <specimen-meta-view :probe="probe" :specimens="specimens"/>
        <template v-slot:actions>
          <edit-probe-specimen-meta-button :specimens="specimens" :probe="probe"/>
        </template>
      </actionable-view>

      <template v-if="probe.specimenSource === 'HUMAN'">
        <h3 class="mt-5">{{ $t('patient._name') }}</h3>
        <actionable-view>
          <patient-view :probe="probe"/>
          <template v-slot:actions>
            <edit-probe-patient-button :probe="probe"/>
          </template>
        </actionable-view>
      </template>

      <template v-if="probe.specimenSource === 'ANIMAL'">
        <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
        <actionable-view>
          <owner-view :probe="probe"/>
          <template v-slot:actions>
            <edit-probe-owner-button :probe="probe"/>
          </template>
        </actionable-view>
      </template>
    </div>
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe.progress') }}</h3>

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

export default {
  emits: ['added'],
  components: {
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
