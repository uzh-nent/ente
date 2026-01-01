<template>
  <div>
    <labeled-value :label="$t('observation.effective_at')">
      {{ formatDateTime(observation.effectiveAt) }}
    </labeled-value>

    <labeled-value :label="$t(`observation._name`)">
      <test-interpretation-badge :observation="observation" />
    </labeled-value>

    <div v-if="observation.interpretationText" class="mt-3 whitespace-preserve-newlines p-2 bg-light">
      {{ observation.interpretationText }}
    </div>
  </div>
</template>

<script>

import {formatDate, formatDateTime, formatOrganism} from "../../../services/domain/formatter";
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
