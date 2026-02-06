<template>
  <tr>
    <td>
      {{practitioner.title}}
      {{ practitioner.givenName }}
      <b>{{ practitioner.familyName }}</b>
    </td>
    <td class="whitespace-preserve-newlines">
      {{ practitioner.addressLines }}
    </td>
    <td>
      {{ city }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ practitioner.contact }}
    </td>
    <td class="w-minimal text-end">
      <add-probe-filter-button :query="{'ordererPrac': this.practitioner['@id']}" />
    </td>
    <td class="w-minimal text-end">
      <edit-practitioner-button :practitioner="practitioner" />
    </td>
  </tr>
</template>

<script>
import {formatAddressCity} from "../../services/domain/formatter";
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
    city: function () {
      return formatAddressCity(this.practitioner)
    }
  }
}

</script>
