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

      <template v-if="probe.specimenSource === 'HUMAN'">
        <h3 class="mt-5">{{ $t('patient._name') }}</h3>
        <patient-view :probe="probe"/>
      </template>

      <template v-if="probe.specimenSource === 'ANIMAL'">
        <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
        <owner-view :probe="probe"/>
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

export default {
  emits: ['added'],
  components: {
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
