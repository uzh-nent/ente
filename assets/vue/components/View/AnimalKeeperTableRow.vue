<template>
  <tr>
    <td>{{ animalKeeper.name }}</td>
    <td class="whitespace-preserve-newlines">
      {{ animalKeeper.addressLines }}
    </td>
    <td>
      {{ city }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ animalKeeper.contact }}
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
import {formatAddressCity} from "../../services/domain/formatter";
import EditAnimalKeeperButton from "../Action/EditAnimalKeeperButton.vue";
import {router} from "../../services/api";
import AddProbeFilterButton from "./Probe/AddProbeFilterButton.vue";

export default {
  components: {AddProbeFilterButton, EditAnimalKeeperButton},
  props: {
    animalKeeper: {
      type: Object,
      required: true
    },
  },
  computed: {
    city: function () {
      return formatAddressCity(this.animalKeeper)
    },
    filterProbesUrl: function () {
      return router.probesView({"animalKeeper": this.animalKeeper['@id']})
    }
  }
}

</script>

