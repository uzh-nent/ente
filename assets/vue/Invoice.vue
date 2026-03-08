<template>
  <probe-table :organisms="organisms" :specimens="specimens" view="invoice" />
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
  beforeMount() {
    const {organisms,specimens} = preloadApi.getAllProbes()
    this.organisms = organisms
    this.specimens = specimens
  }
}
</script>
