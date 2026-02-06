<template>
  <probe-table :organisms="organisms" :specimens="specimens" :url-filter="filter" />
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
import ProbeTable from "./components/View/ProbeTable.vue";

export default {
  emits: ['added'],
  components: {
    ProbeTable,
  },
  data() {
    return {
      organisms: undefined,
      specimens: undefined,
      filter: undefined
    }
  },
  beforeMount() {
    const {organisms,specimens} = preloadApi.getAllProbes()
    this.organisms = organisms
    this.specimens = specimens

    const params = new URLSearchParams(window.location.search);
    this.filter = Object.fromEntries(params.entries());
  }
}
</script>
