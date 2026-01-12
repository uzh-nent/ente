<template>
  <tr>
    <td>{{ $t(`probe._analysis_type.${observation.analysisType}`) }}</td>
    <td>
      <template v-if="observation.analysisType === 'IDENTIFICATION'">
        {{ organism }}
        <span v-if="observation.interpretation === 'NEG'" class="badge bg-danger">
          {{ $t(`observation.identification_failed`) }}
        </span>
        <span class="d-block" v-if="observation.cgMLST">
          {{ $t(`observation.cgMLST`) }}: {{observation.cgMLST}}
        </span>
      </template>
      <template v-else>
        <test-interpretation-badge :observation="observation" />
      </template>
      <span class="d-block whitespace-preserve-newlines" v-if="observation.interpretationText">
        {{observation.interpretationText}}
      </span>
    </td>
    <td>{{ formatDateTime(observation.effectiveAt) }}</td>
    <td>
      <attribution-view :users="users" :entity="observation" />
    </td>
    <td>
      <edit-observation-button :probe="probe" :organisms="organisms" :observation="observation"/>
    </td>
  </tr>
</template>

<script>

import {formatDate, formatDateTime, formatOrganism} from "../../services/domain/formatter";
import AttributionView from "./AttributionView.vue";
import TestInterpretationBadge from "./Observation/TestInterpretationBadge.vue";
import EditObservationButton from "../Action/EditObservationButton.vue";

export default {
  components: {EditObservationButton, TestInterpretationBadge, AttributionView},
  methods: {formatDate, formatDateTime},
  props: {
    observation: {
      type: Object,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
    probe: {
      type: Object,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  },
  computed: {
    organism: function () {
      if (this.observation.interpretation !== 'POS') {
        return null
      }

      return formatOrganism(this.organisms.find(o => o['@id'] === this.observation.organism))
    }
  }
}

</script>
