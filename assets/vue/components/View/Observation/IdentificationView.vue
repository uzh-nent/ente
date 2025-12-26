<template>
  <div>
    <labeled-value :label="$t('observation.analysis_stop_at')">
      {{ formatDate(observation.analysisStopAt) }}
    </labeled-value>

    <labeled-value :label="$t(`probe._analysis_type.IDENTIFICATION`)">
      <template v-if="observation.interpretation ==='POS'">
        {{ organism }}
      </template>
      <template v-if="observation.interpretation ==='NEG'">
        <span class="badge bg-danger">
          {{ $t(`messages.failed`) }}
        </span>
      </template>
    </labeled-value>

    <labeled-value :label="$t(`observation.cgMLST`)" v-if="observation.interpretation ==='POS'">
      {{ observation.cgMLST }}
    </labeled-value>

    <div v-if="observation.interpretationText" class="mt-3 whitespace-preserve-newlines p-2 bg-light">
      {{ observation.interpretationText }}
    </div>
  </div>
</template>

<script>

import {formatDate, formatOrganism} from "../../../services/domain/formatter";
import LabeledValue from "../../Library/View/LabeledValue.vue";

export default {
  components: {LabeledValue},
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

      return formatOrganism(this.organisms.find(o => o['@id'] === this.observation.organism))
    }
  }
}

</script>
