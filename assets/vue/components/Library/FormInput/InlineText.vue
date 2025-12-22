<template>
  <div ref="container" contenteditable="true" class="user-value" :class="{'white-space-pre-wrap': multiLine}"
       @keydown.enter="enterPressed" @focusout="focusOut" @keydown.esc="stopEditing">
    {{ localValue }}
  </div>
</template>

<script>

import { requiredRule } from '../../Form/utils/form'

export default {
  emits: ['blur', 'update:modelValue', 'shift-enter-pressed'],
  data () {
    return {
      localValue: ''
    }
  },
  props: {
    modelValue: {
      type: String,
      default: null
    },
    multiLine: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    modelValue: {
      immediate: true,
      handler: function (newValue) {
        this.localValue = newValue
      }
    }
  },
  computed: {
    required: function () {
      return this.field.rules.includes(requiredRule)
    }
  },
  methods: {
    focusOut: function () {
      this.$emit('blur')

      // decode html entities and placesholders by read from a textarea
      const textarea = document.createElement('textarea')
      textarea.innerHTML = this.$refs.container.innerHTML
      this.localValue = textarea.value
        .replace(/(<([^>]+)>)/gi, '')

      if (this.localValue === '') {
        this.localValue = null
      }

      this.$emit('update:modelValue', this.localValue)
    },
    enterPressed: function ($event) {
      if ($event.shiftKey) {
        this.$emit('shift-enter-pressed', $event)

        return
      }

      this.stopEditing($event)
    },
    stopEditing: function ($event) {
      $event.preventDefault()
      $event.target.blur()
    }
  }
}
</script>

<style scoped>
.white-space-pre-wrap {
  white-space: pre-wrap;
}
</style>
