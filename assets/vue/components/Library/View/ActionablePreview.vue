<template>
  <div class="preview">
    <slot/>
    <div class="actions">
      <slot name="actions"/>
    </div>
  </div>
</template>

<script>

export default {}

</script>

<style scoped>
.preview {
  position: relative;
}

.actions {
  position: absolute;
  right: 0;
  top: 0;
}
</style>
