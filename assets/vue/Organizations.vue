<template>
  <add-organization-button/>
  <organization-table/>
</template>

<script>
import {api} from "./services/api";
import OrganizationTable from "./components/View/OrganizationTable.vue";
import AddOrganizationButton from "./components/Action/AddOrganizationButton.vue";

export default {
  components: {AddOrganizationButton, OrganizationTable},
}

</script>
