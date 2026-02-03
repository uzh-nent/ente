<template>
  <tr>
    <td>
      <b>{{ probe.identifier }}</b> <br/>
      {{ probe.requisitionIdentifier }}
    </td>
    <td>
      {{ service }}
    </td>
    <td>
      {{ probe.observations?.length }}
    </td>
    <td>
      <a class="btn btn-outline-secondary" :href="viewProbeLink">
        <i v-if="this.probe.finishedAt" class="fas fa-eye"></i>
        <i v-else class="fas fa-edit"></i>
      </a>
    </td>
  </tr>
</template>

<script>

import {formatDateTime, formatOrganism, formatProbeService} from "../../services/domain/formatter";
import {router} from "../../services/api";

export default {
  props: {
    probe: {
      type: Object,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    }
  },
  computed: {
    organism: function () {
      if (!this.observation) {
        return null
      }

      return formatOrganism(this.organisms.find(o => o['@id'] === this.observation))
    },
    service: function () {
      return formatProbeService(this.probe, this.$t)
    },
    viewProbeLink: function () {
      return this.probe.finishedAt ? router.linkToProbe(this.probe) : router.linkToActiveProbe(this.probe)
    }
  },
  methods: {
    formatDateTime,
  }
}

</script>
