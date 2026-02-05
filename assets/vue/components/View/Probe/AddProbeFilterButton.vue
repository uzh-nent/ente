<template>
      <a class="btn btn-outline-secondary" :href="filterProbesUrl">
        <i class="fas fa-filter"></i>
      </a>
</template>

<script>

import {router} from "../../../services/api";

export default {
  props: {
    query: {
      type: Object,
      required: true
    },
  },
  computed: {
    filterProbesUrl: function () {
      return router.probesView(this.query)
    }
  }
}

</script>

