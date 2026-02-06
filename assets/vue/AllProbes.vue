<template>
  <probe-table :organisms="organisms" :specimens="specimens" :url-filter="filter" />
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
