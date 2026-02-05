<template>
  <tr>
    <td>
      <b>{{ probe.identifier }}</b> <br/>
      {{ probe.requisitionIdentifier }}
    </td>
    <td>
      {{ service }}
    </td>
    <td>
      <span class="whitespace-preserve-newlines">
        {{ ordererLines.join("\n") }}
      </span>
    </td>
    <td>
      <span v-if="probe.specimenSource === 'HUMAN'" class="d-block">
        {{ probe.patientGivenName }} <b>{{ probe.patientFamilyName }}</b>
        <span v-if="probe.patientGender" class="ms-1">{{ $t('patient._gender_short.' + probe.patientGender) }}</span>
        {{ formatDate(probe.patientBirthDate) }}
        <br/>

        {{ specimen?.displayName }} {{ probe.specimenText }}
        <template v-if="probe.specimenIsolate">
          <span class="badge bg-secondary ms-2">{{ $t('probe.specimen_isolate') }}</span>
        </template>
      </span>
      <span v-else class="d-block">
        <b v-if="probe.animalKeeper">{{ probe.animalKeeperName }}<br/></b>
        {{ formatSpecimenSourceText(probe, $t) }}
      </span>
    </td>
    <td>
      {{ formatDate(probe.analysisStartDate) }}
    </td>
    <td>
      <short-observation-badge
          class="d-inline-block me-1"
          v-for="observation in probe.observations" :key="observation['@id']"
          :observation="observation" :organisms="organisms"/>
    </td>
    <td>
      <a class="btn btn-outline-secondary" :href="viewProbeLink">
        <i v-if="this.probe.finishedAt" class="fas fa-eye"></i>
        <i v-else class="fas fa-edit"></i>
      </a>
    </td>
  </tr>
</template>

<script>

import {
  formatDate,
  formatDateTime,
  formatOrganism,
  formatOrganizationShort, formatPatientName, formatPatientShort,
  formatProbeService, formatSpecimenSourceText
} from "../../services/domain/formatter";
import {router} from "../../services/api";
import LabeledValue from "../Library/View/LabeledValue.vue";
import ShortObservationBadge from "./Observation/ShortObservationBadge.vue";

export default {
  components: {ShortObservationBadge, LabeledValue},
  props: {
    probe: {
      type: Object,
      required: true
    },
    specimens: {
      type: Array,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  },
  computed: {
    specimen: function () {
      return this.specimens.find(s => s['@id'] === this.probe.specimen)
    },
    service: function () {
      return formatProbeService(this.probe, this.$t)
    },
    ordererLines: function () {
      return [
        this.probe.ordererOrgName,
        [this.probe.ordererPracTitle, this.probe.ordererPracGivenName, this.probe.ordererPracFamilyName].filter(e => e).join(" "),
      ].filter(e => e)
    },
    sourceLines: function () {
      return [
        [this.probe.patientGivenName, this.probe.patientFamilyName].filter(e => e).join(" "),
        this.probe.animalKeeperName,
      ].filter(e => e)
    },
    viewProbeLink: function () {
      return this.probe.finishedAt ? router.probeView(this.probe) : router.probeActiveView(this.probe)
    }
  },
  methods: {
    formatDate,
    formatSpecimenSourceText,
    formatPatientName,
    formatDateTime,
  }
}

</script>
