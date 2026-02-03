<template>
  <p ref="value" class="d-inline-block mb-0" :title="title">
    <slot/>
  </p>
</template>

<script>

import {Tooltip} from 'bootstrap'

export default {
  data() {
    return {
      instance: null
    }
  },
  props: {
    title: {
      type: String,
      required: true
    },
    show: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    show: {
      handler: function (value) {
        if (value) {
          this.initialize()
        } else {
          this.dispose()
        }
      }
    }
  },
  methods: {
    initialize: function () {
      if (this.instance) {
        return
      }

      if (!this.$refs.value) {
        return;
      }

      this.instance = new Tooltip(this.$refs.value)
    },
    dispose: function () {
      if (!this.instance) {
        return
      }

      this.instance.dispose()
      this.instance = null;
    },
  },
  mounted() {
    if (this.show) {
      this.initialize()
    }
  },
  unmounted() {
    this.dispose()
  }
}
</script>
