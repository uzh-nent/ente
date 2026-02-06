<template>
  <div class="d-flex gap-2 flex-column bg-light p-2">
    <span class="d-block">
      {{practitioner.title}}
      {{ practitioner.givenName}}
      <b>{{ practitioner.familyName }}</b>
    </span>
    <span v-if="address" class="whitespace-preserve-newlines">
      {{ address }}
    </span>
    <span v-if="practitioner.contact" class="whitespace-preserve-newlines">
      {{ practitioner.contact }}
    </span>
  </div>
</template>

<script>
import {formatAddress} from "../../services/domain/formatter";

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
  }
}

</script>
