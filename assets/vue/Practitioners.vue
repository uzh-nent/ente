<template>
  <add-practitioner-button @added="$refs.table.reload()" />
  <practitioner-table class="mt-2" ref="table" />
</template>

<script>
import PractitionerTable from "./components/View/PractitionerTable.vue";
import AddPractitionerButton from "./components/Action/AddPractitionerButton.vue";

export default {
  components: {AddPractitionerButton, PractitionerTable},
}

</script>
