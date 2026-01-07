<template>
  <table class="table table-striped table-hover border">
    <tbody>
    <report-table-row
        v-for="report in reports" :key="report['@id']" :probe="probe" :report="report" :users="users" />
    </tbody>
  </table>
</template>

<script>

import {formatDate, formatDateTime} from "../../services/domain/formatter";
import ElmReportTableRow from "./ElmReportTableRow.vue";
import ReportTableRow from "./ReportTableRow.vue";

export default {
  components: {ReportTableRow, ElmReportTableRow},
  methods: {formatDateTime, formatDate},
  props: {
    probe: {
      type: Object,
      required: true
    },
    reports: {
      type: Array,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
  }
}

</script>
