<template>
  <select
    :id="id" class="form-control" :required="required" :multiple="multiple"
    :class="{'is-valid': field?.valid, 'is-invalid': field?.invalid }"
    v-model="internalModelValue"
    @blur="$emit('blur')">
    <option :value="null" v-if="!required && !multiple">&mdash;</option>
    <slot />
  </select>
  <span class="form-text" v-if="multiple">{{ $t('_library.ctrl_for_multi_select') }}</span>
  <invalid-feedback :field="field" />
</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import { requiredRule } from '../../Form/utils/form'

export default {
  components: { InvalidFeedback },
  emits: ['blur', 'update:modelValue'],
  data () {
    return {
      internalModelValue: null
    }
  },
  props: {
    modelValue: {
      type: [String, Array, Object],
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
    multiple: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    modelValue: {
      immediate: true,
      handler: function (newValue) {
        this.internalModelValue = newValue
      }
    },
    internalModelValue: function (newValue) {
      this.$emit('update:modelValue', newValue)
    }
  },
  computed: {
    required: function () {
      return this.field?.rules.includes(requiredRule)
    }
  }
}
</script>
