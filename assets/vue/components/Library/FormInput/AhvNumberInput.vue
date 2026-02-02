<template>
  <input
    :id="id" class="form-control" type="text" :required="required"
    :class="{'is-valid': field?.valid, 'is-invalid': field?.invalid }"
    :value="internalModelValue" :disabled="disabled"
    @focus="onAhvNumberFocus" @blur="onAhvNumberBlur"
    @input="internalModelValue = $event.target.value">
</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import { requiredRule } from '../../Form/utils/form'

export default {
  components: { InvalidFeedback },
  emits: ['blur', 'update:modelValue'],
  props: {
    modelValue: {
      type: String,
      default: null
    },
    id: {
      type: String,
      required: true
    },
    field: {
      type: Object,
      default: null
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    modelValue: {
      handler: function (newValue) {
        this.$emit('update:modelValue', newValue)
      }
    }
  },
  computed: {
    required: function () {
      return this.field?.rules.includes(requiredRule)
    },
    internalModelValue: {
      get() {
        return this.formatAhvForInput(this.modelValue)
      },
      set(value) {
        const newValue = this.normalizeAhvInput(value)
        this.$emit('update:modelValue', newValue)
      }
    }
  },
  methods: {
    onAhvNumberFocus() {
      if (!this.internalModelValue) {
        this.internalModelValue = '756'
      }
    },
    onAhvNumberBlur() {
      if (this.internalModelValue === '756') {
        this.internalModelValue = null
      }

      this.$emit('blur')
    },
    normalizeAhvInput(value) {
      if (value === null || value === undefined) {
        return null
      }

      const digitsOnly = String(value).replace(/\D+/g, '')
      return digitsOnly === '' ? null : digitsOnly
    },
    formatAhvForInput(value) {
      if (!value) {
        return null
      }

      const digits = String(value).replace(/\D+/g, '')
      const parts = [
        digits.slice(0, 3),
        digits.slice(3, 7),
        digits.slice(7, 11),
        digits.slice(11, 13),
      ].filter(p => p.length > 0)

      return parts.join('.')
    },
  }
}
</script>
