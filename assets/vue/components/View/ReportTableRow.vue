<template>
  <tr>
    <td>
      {{ report.title }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ receivers }}
    </td>
    <td>
      <attribution-view :users="users" :entity="report"/>
    </td>
    <td>
      <a :href="pdfLink" target="_blank">
        <i class="fas fa-download"></i>
      </a>
    </td>
  </tr>
</template>

<script>
import AttributionView from "./AttributionView.vue";
import ViewElmReportStepLabel from "../Action/ViewElmReportStepLabel.vue";
import {router} from "../../services/api";

export default {
  components: {
    ViewElmReportStepLabel,
    AttributionView},
  props: {
    report: {
      type: Object,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
  },
  computed: {
    pdfLink: function () {
      return router.linkReportPdf(this.report)
    },
    receivers: function () {
      return (this.report.addresses ?? []).map(a => a.substring(0, a.indexOf("\n"))).join("\n")
    },
  }
}

</script>

