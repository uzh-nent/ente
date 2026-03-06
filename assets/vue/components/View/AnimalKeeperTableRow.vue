<template>
  <tr>
    <td>
      {{ animalKeeper.ber }}<br/>
      {{ formatUidNumber(animalKeeper.uid, "") }}<br/>
    </td>
    <td>
      {{ animalKeeper.name }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ address }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ contact }}
    </td>
    <td class="w-minimal text-end">
      <add-probe-filter-button :query="{'animalKeeper': this.animalKeeper['@id']}" />
    </td>
    <td class="w-minimal text-end">
      <edit-animal-keeper-button :animalKeeper="animalKeeper" />
    </td>
  </tr>
</template>

<script>
import {formatAddress, formatAddressCity, formatContact, formatUidNumber} from "../../services/domain/formatter";
import EditAnimalKeeperButton from "../Action/EditAnimalKeeperButton.vue";
import {router} from "../../services/api";
import AddProbeFilterButton from "./Probe/AddProbeFilterButton.vue";

export default {
  methods: {formatUidNumber},
  components: {AddProbeFilterButton, EditAnimalKeeperButton},
  props: {
    animalKeeper: {
      type: Object,
      required: true
    },
  },
  computed: {
    address: function () {
      return formatAddress(this.animalKeeper)
    },
    filterProbesUrl: function () {
      return router.probesView({"animalKeeper": this.animalKeeper['@id']})
    },
    contact: function () {
      return formatContact(this.animalKeeper)
    }
  }
}

</script>

