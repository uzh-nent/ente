<template>
  <template ref="anchor"/>
  <flat-pickr
      :placeholder="placeholder"
      :id="id" class="form-control" :required="required"
      :model-value="modelValue"
      :config="datePickerConfig"
      ref="flatPickr"
      @blur="$emit('blur')"/>
</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import {requiredRule} from '../../Form/utils/form'
import {toggleAnchorValidity, flatPickr, dateTimeConfig, dateConfig} from '../../../services/flatpickr'

export default {
  components: {InvalidFeedback, flatPickr},
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
  },
  methods: {
    inputUpdated: function (e) {
      const expectedLength = this.format === 'datetime' ? 19 : 10
      let instance = this.$refs.flatPickr?.fp;
      if (e.target.value.length === expectedLength && instance) {
        this.$refs.flatPickr.fp.setDate(e.target.value, true, this.datePickerConfig.altFormat)
        this.$refs.flatPickr.fp.close()
      }
    }
  },
  mounted() {
    this.$refs.flatPickr.fp.altInput.addEventListener('input', this.inputUpdated)
  },
  beforeUnmount() {
    this.$refs.flatPickr.fp.altInput.removeEventListener('input', this.inputUpdated)
  }
}
</script>
