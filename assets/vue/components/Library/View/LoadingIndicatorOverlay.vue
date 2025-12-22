<template>
  <div class="loading-overlay">
    <atom-spinner/>
  </div>
</template>

<script>
import AtomSpinner from "./Base/AtomSpinner.vue";

export default {
  components: {AtomSpinner}
}

</script>

<style scoped>

.loading-overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.4);
  top: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

</style>
