<template>
  <tr>
    <td>
      <attribution-view :users="users" :entity="report"/>
    </td>
    <td>
      {{ leadingCode.displayName }}
      <template v-if="organism">
        <br>{{ organism.displayName }}
      </template>
      <template v-if="report.organismText">
        <br>{{ report.organismText }}
      </template>
    </td>
    <td>
      <view-elm-report-step-label :report="report" step="validation" />
      <view-elm-report-step-label :report="report" step="send" />
      <view-elm-report-step-label :report="report" step="queue" />
    </td>
  </tr>
</template>

<script>
import AttributionView from "./AttributionView.vue";
import ViewElmReportStepLabel from "../Action/ViewElmReportStepLabel.vue";

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
    leadingCodes: {
      type: Array,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  },
  computed: {
    leadingCode: function () {
      return this.leadingCodes.find(lc => lc['@id'] === this.report.leadingCode)
    },
    organism: function () {
      return this.organisms.find(o => o['@id'] === this.report.organism)
    }
  }
}

</script>

