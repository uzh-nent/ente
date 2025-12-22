<template>
  <add-patient-button @added="$refs.table.reload()" />
  <patient-table class="mt-2" ref="table" />
</template>

<script>
import PatientTable from "./components/View/PatientTable.vue";
import AddPatientButton from "./components/Action/AddPatientButton.vue";

export default {
  components: {AddPatientButton, PatientTable},
}

</script>
