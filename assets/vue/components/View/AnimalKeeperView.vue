<template>
  <div class="d-flex gap-2 flex-column">
    <span v-if="animalKeeper.name" class="d-block">
      <b>{{ animalKeeper.name }}</b>
    </span>
    <span v-if="address" class="whitespace-preserve-newlines">
      {{ address }}
    </span>
    <span v-if="animalKeeper.contact" class="whitespace-preserve-newlines">
      {{ animalKeeper.contact }}
    </span>
  </div>
</template>

<script>
import {formatAddress} from "../../services/formatter";

export default {
  props: {
    animalKeeper: {
      type: Object,
      required: true
    },
  },
  computed: {
    address: function () {
      return formatAddress(this.animalKeeper)
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
