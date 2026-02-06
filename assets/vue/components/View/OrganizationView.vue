<template>
  <div class="d-flex gap-2 flex-column bg-light p-2">
    <span v-if="organization.name" class="d-block">
      <b>{{ organization.name }}</b>
    </span>
    <span v-if="address" class="whitespace-preserve-newlines">
      {{ address }}
    </span>
    <span v-if="organization.contact" class="whitespace-preserve-newlines">
      {{ organization.contact }}
    </span>
  </div>
</template>

<script>
import {formatAddress} from "../../services/domain/formatter";

export default {
  props: {
    organization: {
      type: Object,
      required: true
    },
  },
  computed: {
    address: function () {
      return formatAddress(this.organization)
    }
  }
}

</script>
