<template>
  <tr>
    <td>
      <a :href="probeView">
        {{ probe?.identifier }}
      </a>
    </td>
    <td>
      <view-elm-report-step-label :report="elmReport" step="queue"/>
    </td>
    <td>
      <attribution-view :users="users" :entity="elmReport" :show-always-created="true"/>
    </td>
    <td>
      <acknowledge-elm-report-button v-if="elmReport.apiStatus === 'FAILED'" :elm-report="elmReport"/>
    </td>
  </tr>
</template>

<script>
import AttributionView from "./AttributionView.vue";
import ViewElmReportStepLabel from "../Action/ViewElmReportStepLabel.vue";
import AcknowledgeElmReportButton from "../Action/AcknowledgeElmReportButton.vue";
import {router} from "../../domain/api";

export default {
  components: {
    AcknowledgeElmReportButton,
    ViewElmReportStepLabel,
    AttributionView
  },
  props: {
    probe: {
      type: Object,
      required: false
    },
    elmReport: {
      type: Object,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
  },
  computed: {
    probeView: function () {
      if (!this.probe) {
        return null;
      }
      
      return router.probeView(this.probe)
    }
  }
}

</script>

