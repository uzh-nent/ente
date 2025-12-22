<template>
  <button class="btn" :class="active ? 'btn-' + color : 'btn-outline-' + color" @click="tryShow">
    <font-awesome-icon v-if="icon" :icon="icon" />
    <span v-else>{{ title }}</span>

    <modal :title="title" :size="modalSize" :show="show" @hide="tryHide">
      <slot />
      <template #footer>
        <slot name="footer" />
      </template>
    </modal>
  </button>
</template>

<script>
import Modal from './Modal.vue'

export default {
  components: { Modal },
  emits: ['hiding', 'showing'],
  props: {
    title: {
      type: String,
      required: true
    },
    icon: {
      type: Array,
      default: null
    },
    active: {
      type: Boolean,
      default: false
    },
    modalSize: {
      type: String,
      default: 'md',
      validator: value => ['sm', 'md', 'lg', 'xl', 'fullscreen'].includes(value)
    },
    color: {
      type: String,
      default: 'primary'
    }
  },
  data () {
    return {
      show: false
    }
  },
  methods: {
    tryShow: function () {
      if (!this.show) {
        this.show = true
        this.$emit('showing')
      }
    },
    tryHide: function () {
      if (this.show) {
        this.show = false
        this.$emit('hiding')
      }
    }
  }
}
</script>
