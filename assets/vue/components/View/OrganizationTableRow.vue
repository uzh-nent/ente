<template>
  <tr>
    <td>{{ organization.name }}</td>
    <td class="whitespace-preserve-newlines">
      {{ organization.addressLines }}
    </td>
    <td>
      {{ city }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ organization.contact }}
    </td>
    <td class="w-minimal text-end">
      <add-probe-filter-button :query="{'ordererOrg': this.organization['@id']}" />
    </td>
    <td class="w-minimal text-end">
      <edit-organization-button :organization="organization" />
    </td>
  </tr>
</template>

<script>
import {formatAddressCity} from "../../services/domain/formatter";
import EditOrganizationButton from "../Action/EditOrganizationButton.vue";
import AddProbeFilterButton from "./Probe/AddProbeFilterButton.vue";

export default {
  components: {AddProbeFilterButton, EditOrganizationButton},
  props: {
    organization: {
      type: Object,
      required: true
    },
  },
  computed: {
    city: function () {
      return formatAddressCity(this.organization)
    }
  }
}

</script>

