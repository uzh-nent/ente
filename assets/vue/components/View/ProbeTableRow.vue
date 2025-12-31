<template>
  <tr>
    <td>
      {{ probe.identifier }} <br/>
      {{ probe.requisitionIdentifier }}
    </td>
    <td>
      {{ service }}
    </td>
    <td>{{ probe.observations?.length }}</td>
    <td>
      <button class="btn btn-outline-secondary" @click="navigateToProbe(probe)">
        <i class="fas fa-eye"></i>
      </button>
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
    }
  },
  methods: {
    formatDateTime,
    navigateToProbe: function () {
      if (this.probe.finishedAt) {
        router.navigateToProbe(this.probe)
      } else {
        router.navigateToActiveProbe(this.probe)
      }
    }
  }
}

</script>
