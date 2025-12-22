<template>
  <div class="d-flex align-items-stretch">
    <div class="nav flex-column nav-tabs-vertical me-3">
      <button v-for="({ id, label }) in views" :key="id"
              class="nav-link" :class="{'active': activeView === id}"
              @click="$emit('navigated', id)" role="tab">
        {{ label }}
      </button>
    </div>
    <div class="col">
      <slot />
    </div>
  </div>
</template>

<script>

export default {
  emits: ['navigated'],
  props: {
    activeView: {
      type: String,
      required: true
    },
    views: {
      type: Array,
      required: true
    }
  }
}

</script>
