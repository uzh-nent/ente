<template>
  <tr>
    <td>{{ organization.name }}</td>
    <td><pre>{{ organization.addressLines }}</pre></td>
    <td>
      {{ organization.postalCode }}
      {{ organization.city }}
      {{ organization.countryCode }}
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
export default {
  components: {},
  props: {
    organization: {
      type: Object,
      required: true
    },
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
