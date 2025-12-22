<template>
  <span class="badge border clickable" :class="badgeClasses" @click="tryShow" @mouseover="hover = true" @mouseleave="hover = false">
    <font-awesome-icon v-if="icon" :icon="icon" />
    <span v-else>{{ title }}</span>

    <modal :title="title" :size="modalSize" :show="show" @hide="tryHide">
      <slot />
      <template #footer>
        <slot name="footer" />
      </template>
    </modal>
  </span>
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
      show: false,
      hover: false
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
  },
  computed: {
    badgeClasses: function () {
      if (this.hover || this.show) {
        return 'bg-' + this.color + ' text-white'
      } else {
        return 'border-' + this.color + ' text-' + this.color
      }
    }
  }
}
</script>
