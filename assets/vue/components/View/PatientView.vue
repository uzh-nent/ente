<template>
  <div class="d-flex gap-2 flex-column bg-light p-2">
    <span class="d-block">
      <b>{{ formatDate(patient.birthDate) }}</b>
      <span v-if="patient.ahvNumber">
        <br/>
        {{ formatAhvNumber(patient.ahvNumber) }}
      </span>
    </span>
    <span class="d-block">
      {{ patient.givenName }} <b>{{ patient.familyName }}</b>
      <span v-if="patient.gender" class="ms-1">{{ $t('patient._gender_short.' + patient.gender)}}</span>
    </span>
    <span v-if="address" class="whitespace-preserve-newlines">
      {{ address }}
    </span>
  </div>
</template>

<script>
import {formatAddress, formatAhvNumber, formatDate} from "../../services/domain/formatter";

export default {
  methods: {formatAhvNumber, formatDate},
  props: {
    patient: {
      type: Object,
      required: true
    },
  },
  computed: {
    address: function () {
      return formatAddress(this.patient)
    },
  }
}

</script>
