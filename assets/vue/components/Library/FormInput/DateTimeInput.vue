<template>
  <div class="input-group">
    <slot name="before" />
    <span ref="anchor" />
    <flat-pickr
        :placeholder="placeholder"
      :id="id" class="form-control" :required="required"
      :model-value="modelValue"
      :config="datePickerConfig"
      @blur="$emit('blur')" />
    <invalid-feedback :field="field" />
  </div>
</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import { requiredRule } from '../../Form/utils/form'
import { toggleAnchorValidity, flatPickr, dateTimeConfig, dateConfig } from '../../../services/flatpickr'

export default {
  components: { InvalidFeedback, flatPickr },
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
    placeholder: {
      type: String,
      default: null
    },
    field: {
      type: Object,
      default: null
    },
    format: {
      type: String,
      default: 'datetime'
    }
  },
  watch: {
    'field.dirty': function () {
      if (this.field) {
        toggleAnchorValidity(this.$refs.anchor, this.field)
      }
    },
    'field.valid': function () {
      if (this.field) {
        toggleAnchorValidity(this.$refs.anchor, this.field)
      }
    },
    'field.invalid': function () {
      if (this.field) {
        toggleAnchorValidity(this.$refs.anchor, this.field)
      }
    }
  },
  computed: {
    required: function () {
      return this.field?.rules.includes(requiredRule)
    },
    datePickerConfig: function () {
      const formatConfig = this.format === 'datetime' ? dateTimeConfig : dateConfig
      return {
        ...formatConfig,
        allowInput: true,
        onValueUpdate: (_, string) => {
          this.$emit('update:modelValue', string || null)
        },
        onClose: (_, string) => {
          this.$emit('update:modelValue', string || null)
        },
        onChange: (_, string) => {
          this.$emit('update:modelValue', string || null)
        }
      }
    }
  }
}
</script>
