<template>
  <add-organization-button @added="$refs.table.reload()" />
  <organization-table class="mt-2" ref="table" />
</template>

<script>
import OrganizationTable from "./components/View/OrganizationTable.vue";
import AddOrganizationButton from "./components/Action/AddOrganizationButton.vue";
import DuckWalking from "./components/Library/View/Base/DuckWalking.vue";

export default {
  components: {DuckWalking, AddOrganizationButton, OrganizationTable},
}

</script>
