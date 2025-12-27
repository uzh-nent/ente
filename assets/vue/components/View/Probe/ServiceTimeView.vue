<template>
  <div>
    <labeled-value :label="$t('probe.received_date')">
      {{ formatDate(probe.receivedDate) }}
    </labeled-value>

    <labeled-value :label="$t('probe.analysis_start_date')">
      {{ formatDate(probe.analysisStartDate) }}
    </labeled-value>

    <labeled-value :label="$t('probe.finished_at')" v-if="probe.finishedAt">
      {{ formatDate(probe.finishedAt) }}
    </labeled-value>
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import {formatDate} from "../../../services/domain/formatter";

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
