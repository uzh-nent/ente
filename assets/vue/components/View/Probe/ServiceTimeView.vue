<template>
  <div>
    <labeled-value :label="$t('probe.created_at')">
      {{ formatDateTime(probe.createdAt) }} / {{ createdBy?.abbreviation }}
    </labeled-value>

    <labeled-value class="mt-2" :label="$t('probe.received_date')">
      {{ formatDate(probe.receivedDate) }}
    </labeled-value>

    <labeled-value :label="$t('probe.analysis_start_date')">
      {{ formatDate(probe.analysisStartDate) }}
    </labeled-value>

    <labeled-value class="mt-2" :label="$t('probe.finished_at')" v-if="probe.finishedAt">
      {{ formatDateTime(probe.finishedAt) }} / {{ finishedBy?.abbreviation }}
    </labeled-value>
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import {formatDate, formatDateTime} from "../../../services/domain/formatter";
import AttributionView from "../AttributionView.vue";

export default {
  methods: {formatDateTime, formatDate},
  components: {AttributionView, LabeledValue},
  props: {
    probe: {
      type: Object,
      required: true
    },
    users: {
      type: Array,
      required: true
    }
  },
  computed: {
    createdBy: function () {
      return this.users.find(u => u['@id'] === this.probe.createdBy)
    },
    finishedBy: function () {
      return this.users.find(u => u['@id'] === this.probe.finishedBy)
    },
  }
}

</script>
