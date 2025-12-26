<template>
  <patient-view :patient="patient" />
</template>

<script>
import AnimalKeeperView from "../AnimalKeeperView.vue";
import LabeledValue from "../../Library/View/LabeledValue.vue";
import {preloadApi} from "../../../services/api";
import PatientView from "../PatientView.vue";
import {probeConverter} from "../../../services/domain/converters";

export default {
  components: {PatientView, LabeledValue, AnimalKeeperView},
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
  computed: {
    patient: function () {
      return probeConverter.reconstructPatient(this.probe)
    },
  },
}

</script>
