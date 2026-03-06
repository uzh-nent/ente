<template>
  <div class="d-flex gap-2 flex-column bg-light p-2">
    <span v-if="businessIdentifiers" class="whitespace-preserve-newlines">
      {{ businessIdentifiers }}
    </span>
    <span v-if="animalKeeper.name" class="d-block">
      <b>{{ animalKeeper.name }}</b>
    </span>
    <span v-if="address" class="whitespace-preserve-newlines">
      {{ address }}
    </span>
    <span v-if="contact" class="whitespace-preserve-newlines">
      {{ contact }}
    </span>
  </div>
</template>

<script>
import {formatAddress, formatBusinessIdentifiers, formatContact} from "../../services/domain/formatter";

export default {
  props: {
    animalKeeper: {
      type: Object,
      required: true
    },
  },
  computed: {
    businessIdentifiers: function () {
      return formatBusinessIdentifiers(this.animalKeeper)
    },
    address: function () {
      return formatAddress(this.animalKeeper)
    },
    contact: function () {
      return formatContact(this.animalKeeper)
    }
  }
}

</script>
