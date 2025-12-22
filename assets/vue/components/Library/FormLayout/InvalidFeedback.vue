<template>
  <div class="invalid-feedback" v-if="field?.errors">
      <span v-for="error in field.errors" :key="error">{{ $t(error) }}<br></span>
  </div>
</template>

<script>
export default {
  props: {
    field: {
      type: Object,
      default: null
    }
  }
}
</script>
