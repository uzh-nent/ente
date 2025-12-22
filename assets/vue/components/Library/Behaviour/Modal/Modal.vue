<template>
  <teleport to="body" v-if="render">
    <div class="modal-backdrop fade" :class="{'show': internalShow}" @click="$emit('hide')" />
    <div class="modal fade d-block" :class="{'show': internalShow}" ref="modal" @keyup.esc.stop="$emit('hide')"
         @mousedown="lastMouseDownEvent = $event"
         @mouseup.self="mouseUpOutside" id="modal" tabindex="-1" role="dialog"
         aria-labelledby="modal-title"
         aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" :class="'modal-' + size" role="document">
        <div class="modal-content shadow">
          <div class="modal-header">
            <slot name="header">
              <h5 class="modal-title" id="modal-title">{{ title }}</h5>
            </slot>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    @click="$emit('hide')" />
          </div>
          <div class="modal-body">
            <slot />
          </div>
          <slot name="footer" />
        </div>
      </div>
    </div>
  </teleport>
</template>

<script>
export default {
  emits: ['hide', 'hidden'],
  props: {
    show: {
      type: Boolean,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    size: {
      type: String,
      default: 'md',
      validator: value => ['sm', 'md', 'lg', 'xl', 'fullscreen'].includes(value)
    }
  },
  data () {
    return {
      lastMouseDownEvent: null,

      render: false,
      internalShow: false
    }
  },
  watch: {
    show: {
      immediate: true,
      handler: function (newValue) {
        if (newValue) {
          this.render = true
          this.$nextTick(() => {
            this.internalShow = true
            this.$refs.modal.focus()
          })
        } else {
          this.internalShow = false
          setTimeout(() => {
            if (!this.internalShow) {
              this.render = false
              this.$emit('hidden')
            }
          }, 300)
        }
      }
    }
  },
  methods: {
    mouseUpOutside: function (event) {
      if (!this.lastMouseDownEvent) {
        this.$emit('hide')
        return
      }

      const diffX = Math.abs(event.pageX - this.lastMouseDownEvent.pageX)
      const diffY = Math.abs(event.pageY - this.lastMouseDownEvent.pageY)

      if (diffX < 10 && diffY < 10) {
        this.$emit('hide')
      }
    }
  }
}
</script>

<style scoped>
.modal-backdrop {
  /* choose modal backdrop z-index same as modal z-index, to support modal in modal. vuejs-teleport guarantees the expected order in DOM. */
  z-index: 1055
}

</style>
