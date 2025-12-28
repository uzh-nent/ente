<template>
  <div>
    <labeled-value :label="$t('probe.received_date')">
      {{ formatDate(probe.receivedDate) }}
    </labeled-value>

    <labeled-value :label="$t('probe.analysis_start_date')">
      {{ formatDate(probe.analysisStartDate) }}
    </labeled-value>

    <labeled-value :label="$t('probe.finished_at')" v-if="probe.finishedAt">
      {{ formatDateTime(probe.finishedAt) }}
    </labeled-value>
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import {formatDate, formatDateTime} from "../../../services/domain/formatter";

export default {
  methods: {formatDateTime, formatDate},
  components: {LabeledValue},
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
}

</script>
