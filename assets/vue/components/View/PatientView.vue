<template>
  <div class="text-bg-light p-2 d-flex gap-2 flex-column position-relative">
    <span class="d-block">
      <b>{{ formatDate(patient.birthDate) }}</b>
      {{ patient.ahvNumber }}
    </span>
    <span v-if="address" class="whitespace-preserve-newlines">
      <b v-if="personName">{{ personName }}<br/></b>
      {{ address }}
    </span>
    <edit-patient-button class="topright" :patient="patient"/>
  </div>
</template>

<script>
import {formatAddress, formatDate, formatPersonName} from "../../services/formatter";
import EditPatientButton from "../Action/EditPatientButton.vue";

export default {
  methods: {formatDate},
  components: {EditPatientButton},
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
    personName: function () {
      return formatPersonName(this.patient)
    }
  }
}

</script>


<style scoped>
.topright {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
}

</style>
