<template>
  <tr>
    <td>{{ $t(`probe._analysis_type.${observation.analysisType}`) }}</td>
    <td>
      <span class="badge" :class="{
            'bg-warning': !observation.interpretation,
            'bg-danger': observation.interpretation === 'POS' ,
            'bg-success': observation.interpretation === 'NEG'
          }">
        {{ $t(`observation._interpretation.${observation.interpretation ?? 'NONE'}`) }}
      </span>
      <span class="d-block whitespace-preserve-newlines" v-if="observation.interpretationText">
        {{observation.interpretationText}}
      </span>
    </td>
    <td>{{ formatDateTime(observation.effectiveAt) }}</td>
    <td>
      <attribution-view :users="users" :entity="observation" />
    </td>
    <td>
      <edit-test-observation-button :observation="observation"/>
    </td>
  </tr>
</template>

<script>

import {formatDate, formatDateTime, formatOrganism} from "../../services/domain/formatter";
import EditTestObservationButton from "../Action/EditTestObservationButton.vue";
import AttributionView from "./AttributionView.vue";

export default {
  components: {AttributionView, EditTestObservationButton},
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
  }
}

</script>
