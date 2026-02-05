<template>
    <span class="badge" :class="{
          'bg-warning': !observation.interpretation,
          'bg-success': observation.interpretation === 'POS' ,
          'bg-danger': observation.interpretation === 'NEG'
        }">
      {{ content }}
    </span>
</template>

<script>

import TestInterpretationBadge from "./TestInterpretationBadge.vue";
import {formatOrganism} from "../../../services/domain/formatter";

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
    content: function () {
      if (this.observation.analysisType === 'IDENTIFICATION') {
        if (this.observation.interpretation === 'NEG') {
          return this.$t('observation.identification_failed')
        }

        return formatOrganism(this.organisms.find(o => o['@id'] === this.observation.organism))
      }

      return this.$t(`probe._analysis_type_short.${this.observation.analysisType}`)
    }
  }
}

</script>
