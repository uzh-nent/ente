<template>
  <tr>
    <td>
      {{ practitioner.gln }}
    </td>
    <td>
      {{ practitioner.title }}
      {{ practitioner.givenName }}
      <b>{{ practitioner.familyName }}</b>
    </td>
    <td class="whitespace-preserve-newlines">
      {{ address }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ contact }}
    </td>
    <td class="w-minimal text-end">
      <add-probe-filter-button :query="{'ordererPrac': this.practitioner['@id']}"/>
    </td>
    <td class="w-minimal text-end">
      <edit-practitioner-button :practitioner="practitioner"/>
    </td>
  </tr>
</template>

<script>
import {formatAddress, formatAddressCity, formatContact} from "../../services/domain/formatter";
import EditPractitionerButton from "../Action/EditPractitionerButton.vue";
import AddProbeFilterButton from "./Probe/AddProbeFilterButton.vue";

export default {
  components: {AddProbeFilterButton, EditPractitionerButton},
  props: {
    practitioner: {
      type: Object,
      required: true
    },
  },
  computed: {
    address: function () {
      return formatAddress(this.practitioner)
    },
    contact: function () {
      return formatContact(this.practitioner)
    }
  }
}

</script>
