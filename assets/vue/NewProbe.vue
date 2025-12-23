<template>
  <add-probe-view />
</template>

<script>
import PatientTable from "./components/View/PatientTable.vue";
import AddPatientButton from "./components/Action/AddPatientButton.vue";
import AddProbeView from "./components/Action/AddProbeView.vue";

export default {
  components: {AddProbeView, AddPatientButton, PatientTable},
}

</script>
