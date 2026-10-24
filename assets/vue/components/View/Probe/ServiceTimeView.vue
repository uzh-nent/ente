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

    <labeled-value :label="$t('probe.analysis_stop_date')" v-if="probe.analysisStopDate">
      {{ formatDate(probe.analysisStopDate) }}
    </labeled-value>

    <labeled-value class="mt-2" :label="$t('probe.closed_at')" v-if="probe.closedAt">
      {{ formatDateTime(probe.closedAt) }} / {{ closedBy?.abbreviation }}
    </labeled-value>

    <div v-if="probe.comment" class="mt-3 whitespace-preserve-newlines p-2 bg-light">
      {{ probe.comment }}
    </div>
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import {formatDate, formatDateTime} from "../../../domain/formatter";
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
    closedBy: function () {
      return this.users.find(u => u['@id'] === this.probe.closedBy)
    },
  }
}

</script>
