<template>
  <span>
    <span v-for="(probe, index) in probes" :key="probe['@id']">
      <span v-if="index > 0">, </span>
      <a :href="getActiveUrl(probe)">{{probe.identifier}}</a>
    </span>
  </span>
</template>

<script>

import {router} from "../../../services/api";

export default {
  props: {
    probes: {
      type: Array,
      required: true
    },
  },
  methods: {
    getActiveUrl: function (probe) {
      return router.probeActiveView(probe)
    }
  }
}

</script>

