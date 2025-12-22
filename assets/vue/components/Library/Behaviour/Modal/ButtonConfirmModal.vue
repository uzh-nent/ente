<template>
  <button class="btn" :class="active ? 'btn-' + color : 'btn-outline-' + color" @click="tryShow">
    <i v-if="icon" :class="icon"></i>
    <span v-else>{{ title }}</span>

    <confirm-modal
      :title="title" :size="modalSize" :show="show" @hide="tryHide" :color="color"
      :can-confirm="canConfirm" :confirm="confirm" :confirm-label="confirmLabel"
      :disable-loading-animation="disableLoadingAnimation"
      :can-abort="canAbort" :abort="abort" :abort-label="abortLabel">
      <slot />
    </confirm-modal>
  </button>
</template>

<script>
import ConfirmModal from './ConfirmModal.vue'

export default {
  components: { ConfirmModal },
  emits: ['hiding', 'showing'],
  props: {
    title: {
      type: String,
      required: true
    },
    icon: {
      type: String,
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
    },
    confirmLabel: {
      type: String,
      required: true
    },
    canConfirm: {
      type: Boolean,
      default: true
    },
    confirm: {
      type: Function,
      required: true
    },
    disableLoadingAnimation: {
      type: Boolean,
      default: false
    },
    abortLabel: {
      type: String,
      default: null
    },
    canAbort: {
      type: Boolean,
      default: true
    },
    abort: {
      type: Function,
      default: null
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
        this.hiding = true
        this.$emit('hiding')
      }
    }
  }
}
</script>
