<template>
  <div>
    <a class="no-underline" href="#" @click="tryShow" :class="labelClass">
      <slot name="label"/>
      <i v-if="icon" :class="icon"></i>
      {{ label }}
    </a>

    <modal :title="title" :size="modalSize" :show="show" @hide="tryHide">
      <slot/>
      <template #footer>
        <slot name="footer"/>
      </template>
    </modal>
  </div>
</template>

<script>
import Modal from './Modal.vue'

export default {
  components: {Modal},
  emits: ['hiding', 'showing'],
  props: {
    label: {
      type: String,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    icon: {
      type: [Array, String],
      default: null
    },
    active: {
      type: Boolean,
      default: false
    },
    labelColor: {
      type: String,
      default: null,
      validator: value => ['success', 'danger'].includes(value)
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
  data() {
    return {
      show: false
    }
  },
  computed: {
    labelClass: function () {
      if (!this.labelColor) {
        return null
      }

      return 'text-' + this.labelColor
    }
  },
  methods: {
    tryShow: function (e) {
      e.preventDefault()
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

<style scoped>
.no-underline {
  text-decoration: none;
}
</style>
