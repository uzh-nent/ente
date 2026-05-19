<template>
  <button class="btn" :class="active ? 'btn-' + color : 'btn-outline-' + color" @click="tryShow">
    <i v-if="icon" :class="icon"></i>
    <template v-if="buttonSize !== 'sm' || !icon">&nbsp;{{ title }}</template>

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
    titleContext: {
      type: String,
      default: null
    },
    icon: {
      type: Array,
      default: null
    },
    active: {
      type: Boolean,
      default: false
    },
    buttonSize: {
      type: String,
      default: 'md',
      validator: value => ['sm', 'md'].includes(value)
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
