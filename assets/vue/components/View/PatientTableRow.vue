<template>
  <tr>
    <td>
      {{ formatAhvNumber(patient.ahvNumber, "") }}
    </td>
    <td>
      <b>{{ patient.familyName }}</b>
      {{ patient.givenName }}
      <span v-if="patient.gender">{{ $t('patient._gender_short.' + patient.gender)}}</span>
      <br/>
      {{ birthDate }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ address }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ contact }}
    </td>
    <td class="w-minimal text-end">
      <add-probe-filter-button :query="{'patient': this.patient['@id']}" />
    </td>
    <td class="w-minimal text-end">
      <edit-patient-button :patient="patient" />
    </td>
  </tr>
</template>

<script>
import {
  formatAddress,
  formatAhvNumber,
  formatContact,
  formatDate,
} from "../../services/domain/formatter";
import EditPatientButton from "../Action/EditPatientButton.vue";
import AddProbeFilterButton from "./Probe/AddProbeFilterButton.vue";

export default {
  methods: {formatAhvNumber},
  components: {AddProbeFilterButton, EditPatientButton},
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
    birthDate: function () {
      return formatDate(this.patient.birthDate)
    },
    contact: function () {
      return formatContact(this.patient)
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
