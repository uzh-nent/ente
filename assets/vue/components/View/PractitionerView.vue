<template>
  <div class="d-flex gap-2 flex-column bg-light p-2">
    <span class="d-block" v-if="practitioner.gln">
      {{ practitioner.gln }}
    </span>
    <span class="d-block">
      {{ practitioner.title }}
      {{ practitioner.givenName }}
      <b>{{ practitioner.familyName }}</b>
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
import {formatAddress, formatContact} from "../../services/domain/formatter";

export default {
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
