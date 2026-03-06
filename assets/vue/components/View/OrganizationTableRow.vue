<template>
  <tr>
    <td>
      {{ organization.ber }}<br/>
      {{ formatUidNumber(organization.uid, "") }}<br/>
    </td>
    <td>
      {{ organization.name }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ address }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ contact }}
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
import {formatAddress, formatAddressCity, formatContact, formatUidNumber} from "../../services/domain/formatter";
import EditOrganizationButton from "../Action/EditOrganizationButton.vue";
import AddProbeFilterButton from "./Probe/AddProbeFilterButton.vue";

export default {
  methods: {formatUidNumber},
  components: {AddProbeFilterButton, EditOrganizationButton},
  props: {
    organization: {
      type: Object,
      required: true
    },
  },
  computed: {
    address: function () {
      return formatAddress(this.organization)
    },
    contact: function () {
      return formatContact(this.organization)
    }
  }
}

</script>

