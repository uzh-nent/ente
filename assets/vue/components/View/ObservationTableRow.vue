<template>
  <tr>
    <td>{{ $t(`probe._analysis_type.${observation.analysisType}`) }}</td>
    <td>{{ formatDateTime(observation.effectiveAt) }}</td>
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

import {formatDateTime, formatOrganism} from "../../services/domain/formatter";

export default {
  methods: {formatDateTime},
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
