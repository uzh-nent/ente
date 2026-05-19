<template>
  <probe-table :organisms="organisms" :specimens="specimens" :users="users" :url-filter="filter" />
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
      users: undefined,
      filter: undefined
    }
  },
  beforeMount() {
    const {organisms,specimens,users} = preloadApi.getAllProbes()
    this.organisms = organisms
    this.specimens = specimens
    this.users = users

    const params = new URLSearchParams(window.location.search);
    this.filter = Object.fromEntries(params.entries());
  }
}
</script>
