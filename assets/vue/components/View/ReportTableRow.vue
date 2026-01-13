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
    <td class="w-minimal">
      <a class="btn btn-secondary" :href="pdfLink" target="_blank" :id="'download-report-' + this.report['@id']">
        <i class="fas fa-download"></i>
      </a>
    </td>
  </tr>
</template>

<script>
import AttributionView from "./AttributionView.vue";
import ViewElmReportStepLabel from "../Action/ViewElmReportStepLabel.vue";
import {router} from "../../services/api";
import {probeConverter} from "../../services/domain/converters";
import {
  formatOrganizationAddress,
  formatOrganizationShort,
  formatPractitionerAddress, formatPractitionerName, formatPractitionerShort
} from "../../services/domain/formatter";

export default {
  components: {
    ViewElmReportStepLabel,
    AttributionView},
  props: {
    probe: {
      type: Object,
      required: true
    },
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
      const receivers = []
      if (this.probe.ordererOrg) {
        const organization = probeConverter.reconstructOrdererOrgOrganization(this.probe)
        receivers.push(organization.name)
      }

      if (this.probe.ordererPrac) {
        const practitioner = probeConverter.reconstructOrdererPracPractitioner(this.probe)
        receivers.push(formatPractitionerName(practitioner))
      }

      const copyToReceivers = (this.report.copyToAddresses ?? []).map(a => a.name);
      return receivers.concat(copyToReceivers).join("\n")
    },
  }
}

</script>

