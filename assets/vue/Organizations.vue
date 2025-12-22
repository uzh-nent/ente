<template>
  <loading-indicator :spin="false">
    <organization-table />
  </loading-indicator>
</template>

<script>
import {api} from "./services/api";
import LoadingIndicator from "./components/Library/View/LoadingIndicator.vue";
import OrganizationTable from "./components/View/OrganizationTable.vue";

export default {
  components: {OrganizationTable, LoadingIndicator},
  mounted() {
    api.setupErrorNotifications(this.$t)
  }
}

</script>
