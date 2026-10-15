<template>
  <probe-table :organisms="organisms" :specimens="specimens" :users="users" view="invoice" />
</template>

<script>

import ProbeTable from "./components/View/ProbeTable.vue";
import {preloadApi} from "./domain/api";

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
    }
  },
  beforeMount() {
    const {organisms,specimens,users} = preloadApi.getAllProbes()
    this.organisms = organisms
    this.specimens = specimens
    this.users = users
  }
}
</script>
