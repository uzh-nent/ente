<template>
  <modal :title="title" :size="size" :show="show" @hide="tryHide">
    <slot />
    <template #footer>
      <div class="modal-footer">
        <button v-if="abort" type="submit" :disabled="!canAbort" @click="tryAbort" class="btn btn-light me-auto">
          {{ abortLabel ?? $t('_action.abort') }}
        </button>
        <slot name="footer-center" />
        <button type="submit" :disabled="!canConfirm || isConfirming || !show" @click="tryConfirm"
                :class="'btn btn-' + color">
          <span class="d-flex gap-3 align-items-center">
            <looping-rhombus-spinner v-if="isConfirming && !disableLoadingAnimation" class="white" />
            <span>{{ confirmLabel }}</span>
          </span>
        </button>
      </div>
    </template>
  </modal>
</template>

<script>
import Modal from './Modal.vue'
import LoopingRhombusSpinner from '../../View/Base/LoopingRhombusSpinner.vue'

export default {
  components: { LoopingRhombusSpinner, Modal },
  emits: ['hide'],
  props: {
    title: {
      type: String,
      required: true
    },
    size: {
      type: String,
      default: 'md',
      validator: value => ['sm', 'md', 'lg', 'xl', 'fullscreen'].includes(value)
    },
    show: {
      type: Boolean,
      required: true
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
      isConfirming: false
    }
  },
  methods: {
    tryHide: function () {
      if (this.isConfirming) {
        return
      }

      this.$emit('hide')
    },
    tryConfirm: async function () {
      if (!this.canConfirm) {
        return
      }

      this.isConfirming = true

      try {
        await this.confirm()
      } finally {
        window.setTimeout(() => {
          this.isConfirming = false
        }, 300)
        this.$emit('hide')
      }
    },
    tryAbort: async function () {
      if (!this.canAbort || this.isConfirming) {
        return
      }

      this.abort()
      this.$emit('hide')
    }
  }
}
</script>
