<template>
  <div class="form-check" :class="{'mt-form-field': formFieldLayout}">
    <input
      ref="checkbox"
      :id="id" class="form-check-input" type="checkbox" :required="required"
      :class="{'is-valid': field?.valid, 'is-invalid': field?.invalid }"
      :checked="modelValue"
      @change="input">
    <label class="form-check-label" :for="id" v-if="label">
      {{ label }}
      <span v-if="required" class="text-danger">*</span>
    </label>
    <invalid-feedback :field="field" />
  </div>
</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import { requiredRule } from '../../Form/utils/form'

export default {
  components: { InvalidFeedback },
  emits: ['update:modelValue'],
  props: {
    modelValue: {
      type: [Boolean, null],
      default: false
    },
    id: {
      type: String,
      required: true
    },
    field: {
      type: Object,
      default: null
    },
    label: {
      type: String,
      default: null
    },
    formFieldLayout: {
      type: Boolean,
      default: false
    },
    allowIndeterminate: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    modelValue: function (value) {
      this.$refs.checkbox.indeterminate = value === null
    }
  },
  computed: {
    required: function () {
      return this.field?.rules.includes(requiredRule)
    }
  },
  methods: {
    input: function () {
      if (this.modelValue) {
        this.$emit('update:modelValue', false)
      } else if (this.allowIndeterminate && this.modelValue === false) {
        this.$emit('update:modelValue', null)
      } else {
        this.$emit('update:modelValue', true)
      }
    }
  },
  mounted () {
    this.$refs.checkbox.indeterminate = this.modelValue === null
  }
}
</script>

<style scoped>
.mt-form-field {
  margin-top: 1.8em;
}

.clickable {
  cursor: pointer;
}
</style>
