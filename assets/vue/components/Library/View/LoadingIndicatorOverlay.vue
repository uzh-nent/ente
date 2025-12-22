<template>
  <div class="loading-overlay">
    <loading-indicator/>
  </div>
</template>

<script>
import LoadingIndicator from "./LoadingIndicator.vue";

export default {
  components: {LoadingIndicator}
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
