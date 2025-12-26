<template>
  <div class="d-flex">
    <div class="w-50">
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
  }
}

</script>
