<template>
  <tr>
    <td>
      {{ report.title }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ receivers }}
    </td>
    <td>
      <attribution-view :users="users" :entity="report" :show-always-created="true"/>
    </td>
    <td class="w-minimal">
      <send-report-email-button
          v-if="!sentReportEmail && !readonly" class="me-2"
          :report="report" :probe="probe" :users="users" :disabled="!hasMedicalValidation"
          @sent="$emit('sent-email', $event)"/>
      <view-report-email-button
          v-if="sentReportEmail" class="me-2" :report-email="sentReportEmail" :users="users"/>
      <a class="btn btn-secondary" :href="pdfLink" target="_blank" :id="'download-report-' + this.report['@id']">
        <i class="fas fa-download"></i>
      </a>
    </td>
  </tr>
</template>

<script>
import AttributionView from "./AttributionView.vue";
import {probeConverter} from "../../domain/converters";
import {formatPractitionerName} from "../../domain/formatter";
import SendReportEmailButton from "../Action/SendReportEmailButton.vue";
import ViewReportEmailButton from "../Action/ViewReportEmailButton.vue";
import {checkHasMedicalValidation} from "../../domain/authorization";
import {router} from "../../domain/api";

export default {
  emits: ['sent-email'],
  components: {
    ViewReportEmailButton,
    SendReportEmailButton,
    AttributionView
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
    report: {
      type: Object,
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
  computed: {
    pdfLink: function () {
      return router.reportPdf(this.report)
    },
    hasMedicalValidation: function () {
      return checkHasMedicalValidation(this.users)
    },
    sentReportEmail: function () {
      return this.reportEmails.filter(re => re.sentAt)[0]
    },
    receivers: function () {
      const receivers = []
      if (this.probe.ordererOrg) {
        const organization = probeConverter.reconstructOrdererOrg(this.probe)
        receivers.push(organization.name)
      }

      if (this.probe.ordererPrac) {
        const practitioner = probeConverter.reconstructOrdererPrac(this.probe)
        receivers.push(formatPractitionerName(practitioner))
      }

      const copyToReceivers = (this.report.copyToAddresses ?? []).map(a => a.name);
      return receivers.concat(copyToReceivers).join("\n")
    },
  }
}

</script>

