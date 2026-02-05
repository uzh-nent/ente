<template>
  <div>
    <div class="form-check" :class="{'form-check-inline': inline}" v-for="choice in choices" :key="choice.value">
      <input class="form-check-input" type="checkbox" :disabled="checkDisabled(choice.value)"
             :name="id" :id="id + '_' + choice.value" :value="choice.value"
             :checked="modelValue?.includes(choice.value)"
             @change="toggle(choice.value, $event.target.checked)">
      <label class="form-check-label clickable" :for="id + '_' + choice.value">{{ choice.label }}</label>
    </div>
  </div>
</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import {requiredRule} from '../../Form/utils/form'

export default {
  components: {InvalidFeedback},
  emits: ['update:modelValue'],
  props: {
    modelValue: {
      type: Array,
      default: []
    },
    id: {
      type: String,
      required: true
    },
    field: {
      type: Object,
      default: null
    },
    choices: {
      type: Array,
      required: true
    },
    inline: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: [Boolean, Function],
      default: false
    },
  },
  methods: {
    checkDisabled: function (value) {
      if (!this.disabled) {
        return false
      }

      if (typeof this.disabled === 'function') {
        return this.disabled(value)
      }

      return this.disabled

    },
    toggle: function (value, checked) {
      const nextValue = this.modelValue?.filter(v => v !== value) ?? []
      if (checked) {
        nextValue.push(value)
      }
      this.$emit('update:modelValue', nextValue)
    }
  },
}
</script>

<style scoped>
.clickable {
  cursor: pointer;
}
</style>
