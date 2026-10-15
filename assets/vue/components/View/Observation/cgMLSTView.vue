<template>
  <div>
    <labeled-value :label="$t('observation.effective_at')">
      {{ formatDateTime(observation.effectiveAt) }}
    </labeled-value>

    <labeled-value :label="$t(`observation.interpretation_text_cgmlst`)">
      {{ observation.interpretationText }}
    </labeled-value>
  </div>
</template>

<script>

import {formatDate, formatDateTime, formatOrganism} from "../../../domain/formatter";
import LabeledValue from "../../Library/View/LabeledValue.vue";
import TestInterpretationBadge from "./TestInterpretationBadge.vue";

export default {
  components: {TestInterpretationBadge, LabeledValue},
  methods: {formatDateTime, formatDate},
  props: {
    observation: {
      type: Object,
      required: true
    },
  },
}

</script>
