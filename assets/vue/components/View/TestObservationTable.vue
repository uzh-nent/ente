<template>
  <table class="table table-striped table-hover border">
    <thead>
    <tr>
      <th>{{ $t('service.ecoli_identification') }}</th>
      <th>{{ $t('observation._name') }}</th>
      <th>{{ $t('observation.effective_at') }}</th>
      <th></th>
      <th class="w-minimal"></th>
    </tr>
    </thead>
    <tbody>
    <test-observation-table-row
        v-for="observation in observations" :key="observation['@id']"
        :observation="observation" :users="users" />
    </tbody>
  </table>
</template>

<script>

import {formatDate, formatDateTime} from "../../services/domain/formatter";
import TestObservationTableRow from "./TestObservationTableRow.vue";

export default {
  components: {TestObservationTableRow},
  methods: {formatDateTime, formatDate},
  props: {
    observations: {
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
