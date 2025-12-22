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
      <edit-animal-keeper-button :animalKeeper="animalKeeper" />
    </td>
  </tr>
</template>

<script>
import {formatAddressCity} from "../../services/formatter";
import EditAnimalKeeperButton from "../Action/EditAnimalKeeperButton.vue";

export default {
  components: {EditAnimalKeeperButton},
  props: {
    animalKeeper: {
      type: Object,
      required: true
    },
  },
  computed: {
    city: function () {
      return formatAddressCity(this.animalKeeper)
    }
  }
}

</script>


<style scoped>
.btn-wide {
  width: 3em;
}

.btn-xs {
  font-size: 0.6em;
  padding: 0.5em;
}

.lh-0 {
  line-height: 0;
}

.whitespace-nowrap {
  white-space: nowrap;
}

</style>
