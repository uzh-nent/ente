<template>
  <div class="d-flex mb-3">
    <div>
      {{ label }}:
    </div>
    <div class="flex-grow-1">
      <slot />
    </div>
  </div>
</template>

<script>

export default {
  emits: ['navigated'],
  props: {
    label: {
      type: String,
      required: true
    },
  }
}

</script>
