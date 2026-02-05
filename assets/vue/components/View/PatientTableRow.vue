<template>
  <tr>
    <td>
      {{ birthDate }}<br/>
      {{ patient.ahvNumber }}
    </td>
    <td>
      {{ patient.givenName }} {{ patient.familyName }}
      <span v-if="patient.gender">{{ $t('patient._gender_short.' + patient.gender)}}</span>
    </td>
    <td class="whitespace-preserve-newlines">
      {{ patient.addressLines }}
      {{ city }}
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
import {formatAddressCity, formatDate, formatPatientName,} from "../../services/domain/formatter";
import EditPatientButton from "../Action/EditPatientButton.vue";
import AddProbeFilterButton from "./Probe/AddProbeFilterButton.vue";

export default {
  components: {AddProbeFilterButton, EditPatientButton},
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
