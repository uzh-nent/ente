<template>
  <tr>
    <td>
      {{ birthDate }}<br/>
      {{ patient.ahvNumber }}
    </td>
    <td>{{ name }}</td>
    <td class="whitespace-preserve-newlines">
      {{ patient.addressLines }}
      {{ city }}
    </td>
    <td class="w-minimal text-end">
      <edit-patient-button :patient="patient" />
    </td>
  </tr>
</template>

<script>
import {formatAddressCity, formatDate, formatPatientName,} from "../../services/formatter";
import EditPatientButton from "../Action/EditPatientButton.vue";

export default {
  components: {EditPatientButton},
  props: {
    patient: {
      type: Object,
      required: true
    },
  },
  computed: {
    city: function () {
      return formatAddressCity(this.patient)
    },
    birthDate: function () {
      return formatDate(this.patient.birthDate)
    },
    name: function () {
      return formatPatientName(this.patient, this.$t)
    }
  }
}

</script>


<style scoped>
.btn-wide {
  width: 3em;
}

.btn-xs {
  font-size: 0.6em;
  padding: 0.5em;
}

.lh-0 {
  line-height: 0;
}

.whitespace-nowrap {
  white-space: nowrap;
}

</style>
