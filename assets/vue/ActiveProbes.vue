<template>
  <div class="row">
    <div class="pe-4" :class="'col-lg-' + (this.referenceColumnCount * 3)">
      <h2>{{ $t('probe._laboratory_function.REFERENCE') }}</h2>

      <probe-navigation-view :probes="this.referenceProbes" :column-count="this.referenceColumnCount"
                             :focus="this.referenceColumnCount >= this.primaryColumnCount" @navigate="navigateToProbe" />
    </div>
    <div class="ps-4" :class="'col-lg-' + (this.primaryColumnCount * 3)">
      <h2>{{ $t('probe._laboratory_function.PRIMARY') }}</h2>

      <probe-navigation-view :probes="this.primaryProbes" :column-count="this.primaryColumnCount"
                             :focus="this.primaryColumnCount > this.referenceColumnCount"
                             @navigate="navigateToProbe" />
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
import ProbeNavigationView from "./components/View/ProbeNavigationView.vue";

export default {
  emits: ['added'],
  components: {
    ProbeNavigationView,
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
      activeProbes: undefined,
    }
  },
  computed: {
    referenceProbes: function () {
      return this.activeProbes.filter(p => p.laboratoryFunction === 'REFERENCE')
    },
    primaryProbes: function () {
      return this.activeProbes.filter(p => p.laboratoryFunction === 'PRIMARY')
    },
    referenceColumnCount: function () {
      const ratio = (this.referenceProbes.length / this.activeProbes.length) * 4
      return Math.max(1, Math.min(3, Math.round(ratio)))
    },
    primaryColumnCount: function () {
      return 4 - this.referenceColumnCount
    },
  },
  methods: {
    navigateToProbe: function (probe) {
      router.navigateToActiveProbe(probe)
    }
  },
  beforeMount() {
    const {activeProbes} = preloadApi.getActiveProbes()
    this.activeProbes = activeProbes
  }
}
</script>
