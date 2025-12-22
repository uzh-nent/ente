<template>
  <span v-if="value">
    {{ formatted }}
  </span>
  <span v-else>-</span>
</template>

<script>

import {formatDate} from '../../../services/formatter'

export default {
  props: {
    value: {
      type: String,
      default: null
    }
  },
  computed: {
    formatted: function () {
      return formatDate(this.value)
    }
  }
}
</script>
