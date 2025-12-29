<template>
  <div>
    <div class="table-wrapper">
      <table class="table table-striped table-hover border">
        <tbody>
        <elm-report-table-row
            v-for="report in reports" :key="report['@id']" :report="report"
            :users="users" :leading-codes="leadingCodes" :organisms="organisms"/>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>

import {formatDate, formatDateTime} from "../../services/domain/formatter";
import ElmReportTableRow from "./ElmReportTableRow.vue";

export default {
  components: {ElmReportTableRow},
  methods: {formatDateTime, formatDate},
  props: {
    reports: {
      type: Array,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
    leadingCodes: {
      type: Array,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  }
}

</script>

<style scoped>
.table-wrapper {
  position: relative;
}

.reset-table-styles {
  text-align: left;
  font-weight: normal;
}
</style>
