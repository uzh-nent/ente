<template>
  <div class="text-bg-light p-2 d-flex gap-2 flex-column position-relative">
    <span v-if="organization.name" class="d-block">
      <b>{{ organization.name }}</b>
    </span>
    <span v-if="address" class="whitespace-preserve-newlines">
      {{ address }}
    </span>
    <span v-if="organization.contact" class="whitespace-preserve-newlines">
      {{ organization.contact }}
    </span>
    <edit-organization-button class="topright" :organization="organization"/>
  </div>
</template>

<script>
import {formatAddress, formatAddressCity} from "../../services/formatter";
import EditOrganizationButton from "../Action/EditOrganizationButton.vue";

export default {
  components: {EditOrganizationButton},
  props: {
    organization: {
      type: Object,
      required: true
    },
  },
  computed: {
    address: function () {
      return formatAddress(this.organization)
    }
  }
}

</script>


<style scoped>
.topright {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
}

</style>
