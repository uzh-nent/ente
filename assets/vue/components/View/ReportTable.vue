<template>
  <table class="table table-striped table-hover border">
    <tbody>
    <report-table-row
        v-for="report in reports" :key="report['@id']"
        :probe="probe" :report="report" :users="users" :report-emails="filterReportEmails(report)"
        :readonly="readonly"
        @sent-email="$emit('sent-email', $event)"/>
    </tbody>
  </table>
</template>

<script>

import ElmReportTableRow from "./ElmReportTableRow.vue";
import ReportTableRow from "./ReportTableRow.vue";

export default {
  emits: ['sent-email'],
  components: {ReportTableRow, ElmReportTableRow},
  props: {
    probe: {
      type: Object,
      required: true
    },
    reports: {
      type: Array,
      required: true
    },
    reportEmails: {
      type: Array,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
    readonly: {
      type: Boolean,
      default: false
    },
  },
  methods: {
    filterReportEmails: function (report) {
      return this.reportEmails.filter(re => re.report === report['@id'])
    }
  },
}

</script>
