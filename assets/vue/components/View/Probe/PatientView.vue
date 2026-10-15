<template>
  <patient-view :patient="patient" />
</template>

<script>
import AnimalKeeperView from "../AnimalKeeperView.vue";
import LabeledValue from "../../Library/View/LabeledValue.vue";
import PatientView from "../PatientView.vue";
import {probeConverter} from "../../../domain/converters";
import {preloadApi} from "../../../domain/api";

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
