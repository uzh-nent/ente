<template>
  <ul class="nav nav-underline mb-3">
    <li class="nav-item" v-for="({ id, label }) in views" :key="id">
      <a class="nav-link" aria-current="page" href="#"
         :class="{'active': activeView === id}" @click="$emit('navigated', id)">
        <h3 class="mb-0">{{ label }}</h3>
      </a>
    </li>
  </ul>
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
