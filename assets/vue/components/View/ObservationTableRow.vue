<template>
  <tr>
    <td>{{ $t(`probe._analysis_type.${observation.analysisType}`) }}</td>
    <td>{{ formatDate(observation.analysisStopAt) }}</td>
    <td>
        <span class="badge bg-success" v-if="observation.interpretation ==='POS'">
          {{ $t(`message.successful`) }}
        </span>
      <span class="badge bg-success" v-if="observation.interpretation ==='NEG'">
          {{ $t(`message.failed`) }}
        </span>
    </td>
    <td>

    </td>
  </tr>
</template>

<script>

import {formatDate, formatOrganism} from "../../services/domain/formatter";

export default {
  methods: {formatDate},
  props: {
    observation: {
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
    }
  }
}

</script>

<style scoped>
.table-wrapper {
  position: relative;
}

.reset-table-styles {
  text-align: left;
  font-weight: normal;
}
</style>
