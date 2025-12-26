<template>
  <labeled-value :label="$t('probe.received_at')">
    {{ formatDate(probe.receivedAt) }}
  </labeled-value>

  <labeled-value :label="$t('probe.analysis_start_at')">
    {{ formatDate(probe.analysisStartAt) }}
  </labeled-value>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import {formatDate} from "../../../services/formatter";

export default {
  methods: {formatDate},
  components: {LabeledValue},
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
}

</script>
