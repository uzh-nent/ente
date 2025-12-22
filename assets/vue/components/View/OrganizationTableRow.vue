<template>
  <tr>
    <td>{{ organization.name }}</td>
    <td class="whitespace-preserve-newlines">
      {{ organization.addressLines }}
    </td>
    <td>
      {{ city }}
    </td>
    <td class="whitespace-preserve-newlines">
      {{ organization.contact }}
    </td>
    <td class="w-minimal text-end">
      <div class="btn-group ms-1" role="group">
        <a class="btn btn-xs btn-wide btn-secondary"
           href="/">
          <i class="fas fa-eye"></i>
        </a>
      </div>
    </td>
  </tr>
</template>

<script>
import {formatAddressCity} from "../../services/formatter";

export default {
  components: {},
  props: {
    organization: {
      type: Object,
      required: true
    },
  },
  computed: {
    city: function () {
      return formatAddressCity(this.organization)
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
