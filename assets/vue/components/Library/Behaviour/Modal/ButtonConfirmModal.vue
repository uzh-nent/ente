<template>
  <button class="btn" :class="active ? 'btn-' + color : 'btn-outline-' + color" @click="tryShow" ref="button"
          :disabled="disabled">
    <i v-if="icon" :class="icon"></i>
    <template v-if="buttonSize !== 'sm' || !icon">&nbsp;{{ title }}</template>

    <confirm-modal
        :title="title" :size="modalSize" :show="show" @hide="tryHide" :color="color"
        :can-confirm="canConfirm" :confirm="confirm" :confirm-label="confirmLabel"
        :disable-loading-animation="disableLoadingAnimation"
        :can-abort="canAbort" :abort="abort" :abort-label="abortLabel">
      <slot/>
    </confirm-modal>
  </button>
</template>

<script>
import ConfirmModal from './ConfirmModal.vue'

export default {
  components: {ConfirmModal},
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
    disabled: {
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
  data() {
    return {
      show: false
    }
  },
  methods: {
    tryShow: function () {
      if (!this.show) {
        this.show = true
        window.setTimeout(() => this.$emit('showing'), 100)
      }
    },
    tryHide: function () {
      if (this.show) {
        this.show = false
        this.hiding = true
        this.$refs.button?.focus()
        window.setTimeout(() => this.$emit('hiding'), 100)
      }
    }
  }
}
</script>
