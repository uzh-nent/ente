<template>
    <span class="badge" :class="cssClass">
      {{ content }}
    </span>
</template>

<script>

import TestInterpretationBadge from "./TestInterpretationBadge.vue";
import {formatOrganism} from "../../../domain/formatter";
import {isInterpretationAnalysis} from "../../../domain/domain";

export default {
  components: {TestInterpretationBadge},
  props: {
    observation: {
      type: Object,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  },
  computed: {
    cssClass: function () {
      if (isInterpretationAnalysis(this.observation.analysisType)) {
        return 'bg-info'
      }

      if (!this.observation.interpretation) {
        return 'bg-warning'
      }

      return this.observation.interpretation === 'POS' ?  'bg-success' : 'bg-danger'
    },
    content: function () {
      if (this.observation.analysisType === 'IDENTIFICATION') {
        if (this.observation.interpretation === 'NEG') {
          return this.$t(`observation._interpretation_meta_short.${this.observation.interpretationMeta ?? 'NONE'}`)
        }

        return formatOrganism(this.organisms.find(o => o['@id'] === this.observation.organism))
      }

      if (isInterpretationAnalysis(this.observation.analysisType)) {
        return this.observation.interpretationText
      }

      return this.$t(`probe._analysis_type_short.${this.observation.analysisType}`)
    }
  }
}

</script>
