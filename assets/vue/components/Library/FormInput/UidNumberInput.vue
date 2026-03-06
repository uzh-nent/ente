<template>
  <input
    :id="id" class="form-control" type="text" :required="required"
    :class="{'is-valid': field?.valid, 'is-invalid': field?.invalid }"
    :value="internalModelValue" :disabled="disabled"
    @focus="onUidNumberFocus" @blur="onUidNumberBlur"
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
        return this.formatUidForInput(this.modelValue)
      },
      set(value) {
        const newValue = this.normalizeUidInput(value)
        this.$emit('update:modelValue', newValue)
      }
    }
  },
  methods: {
    onUidNumberFocus() {
      if (!this.internalModelValue) {
        this.internalModelValue = 'CHE'
      }
    },
    onUidNumberBlur() {
      if (this.internalModelValue === 'CHE') {
        this.internalModelValue = null
      }

      this.$emit('blur')
    },
    normalizeUidInput(value) {
      if (value === null || value === undefined) {
        return null
      }

      const noSeparators = String(value).replace(/[^\dCHE]/g, '')
      return noSeparators === '' ? null : noSeparators
    },
    formatUidForInput(value) {
      if (!value) {
        return null
      }

      const digits = this.normalizeUidInput(value) ?? ""
      const parts = [
        digits.slice(0, 3),
        digits.slice(3, 6),
        digits.slice(6, 9),
        digits.slice(9, 12),
      ].filter(p => p.length > 0)

      if (parts.length > 1) {
        const first = parts.shift()
        parts[0] = first + '-' + parts[0]
      }
      console.log(digits, parts)

      return parts.join('.')
    },
  }
}
</script>
