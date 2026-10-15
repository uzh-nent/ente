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

import {formatDate, formatDateTime} from "../../../domain/formatter";
import LabeledValue from "../../Library/View/LabeledValue.vue";

export default {
  components: {LabeledValue},
  methods: {formatDateTime, formatDate},
  props: {
    observation: {
      type: Object,
      required: true
    },
  },
}

</script>
