<template>
  <div class="row" v-if="probe">
    <div class="col-lg-4 col-md-6">
      <h3>{{ $t('probe.service_request') }}</h3>
      <service-request-view :probe="probe"/>

      <h3 class="mt-5">{{ $t('probe.orderer') }}</h3>

      <h3 class="mt-5">{{ $t('probe._name') }}</h3>

      <template v-if="probe.specimenSource === 'HUMAN'">
        <h3 class="mt-5">{{ $t('patient._name') }}</h3>
      </template>

      <template v-if="probe.specimenSource === 'ANIMAL'">
        <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
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

export default {
  emits: ['added'],
  components: {
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
