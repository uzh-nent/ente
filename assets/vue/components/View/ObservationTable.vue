<template>
  <table class="table table-striped table-hover border">
    <thead>
    <tr>
      <th>{{ $t('probe.service_request') }}</th>
      <th>{{ $t('observation._name') }}</th>
      <th>{{ $t('observation.effective_at') }}</th>
      <th></th>
      <th class="w-minimal"></th>
    </tr>
    </thead>
    <tbody>
    <observation-table-row
        v-for="observation in observations" :key="observation['@id']"
        :observation="observation" :users="users" :probe="probe" :organisms="organisms" />
    </tbody>
  </table>
</template>

<script>

import {formatDate, formatDateTime} from "../../services/domain/formatter";
import ObservationTableRow from "./ObservationTableRow.vue";

export default {
  components: {ObservationTableRow},
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
    probe: {
      type: Object,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  }
}

</script>
