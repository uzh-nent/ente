<template>
  <input
    :id="id" class="form-control" :type="type" :required="required" :autofocus="autofocus"
    :class="{'is-valid': field?.valid, 'is-invalid': field?.invalid }"
    :value="modelValue" :disabled="disabled" :placeholder="placeholder"
    @input="$emit('update:modelValue', parseValue($event.target.value))"
    @blur="$emit('blur')">
</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import { requiredRule } from '../../Form/utils/form'

export default {
  components: { InvalidFeedback },
  emits: ['blur', 'update:modelValue'],
  props: {
    modelValue: {
      type: [String, Number],
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
    type: {
      type: String,
      default: 'text'
    },
    placeholder: {
      type: String,
      default: null
    },
    disabled: {
      type: Boolean,
      default: false
    },
    autofocus: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    required: function () {
      return this.field?.rules.includes(requiredRule)
    }
  },
  methods: {
    parseValue: function (value) {
      if (value === '') {
        return null
      }

      if (this.type === 'number') {
        return parseInt(value)
      }

      return value
    }
  }
}
</script>
