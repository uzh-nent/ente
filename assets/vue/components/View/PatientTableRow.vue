<template>
  <tr>
    <td>{{ name }}</td>
    <td>
      {{ birthdate }}<br/>
      {{ patient.ahvNumber }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ patient.addressLines }}
    </td>
    <td>
      {{ city }}
    </td>
    <td class="w-minimal text-end">
      <edit-patient-button :patient="patient" />
    </td>
  </tr>
</template>

<script>
import {formatAddressCity, formatDate, formatPersonName} from "../../services/formatter";
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
    birthdate: function () {
      return formatDate(this.patient.birthdate)
    },
    name: function () {
      return formatPersonName(this.patient)
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
