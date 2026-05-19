<template>
  <div class="d-flex">
    <div :class="width" class="flex-shrink-0">
      <u>{{ label }}</u>:&nbsp;
    </div>
    <div class="flex-grow-1">
      <slot/>
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
    width: {
      type: String,
      default: 'w-50'
    },
  }
}

</script>
