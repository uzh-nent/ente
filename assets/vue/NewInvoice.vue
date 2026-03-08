<template>
  <probe-table :organisms="organisms" :specimens="specimens" :hidden-filter="invoicableProbesFilter" view="invoice" />
</template>

<script>

import {preloadApi} from './services/api'
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
    }
  },
  computed: {
    invoicableProbesFilter: function () {
      return {
        invoiceStatus: null,
      }
    },
  },
  beforeMount() {
    const {organisms,specimens} = preloadApi.getAllProbes()
    this.organisms = organisms
    this.specimens = specimens
  }
}
</script>
