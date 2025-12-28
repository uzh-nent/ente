<template>
  <div class="d-flex gap-2 flex-column">
    <span class="d-block">
      <b>{{ formatDate(patient.birthDate) }}</b>
      {{ patient.ahvNumber }}
    </span>
    <span class="d-block">
      {{ patient.givenName }} {{ patient.familyName }}
      <span v-if="patient.gender">{{ $t('patient._gender_short.' + patient.gender)}}</span>
    </span>
    <span v-if="address" class="whitespace-preserve-newlines">
      {{ address }}
    </span>
  </div>
</template>

<script>
import {formatAddress, formatDate} from "../../services/domain/formatter";

export default {
  methods: {formatDate},
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
