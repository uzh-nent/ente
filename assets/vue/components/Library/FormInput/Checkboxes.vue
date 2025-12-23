<template>
  <div>
    <div class="form-check form-check-inline" v-for="choice in choices" :key="choice.value">
      <input class="form-check-input" type="checkbox" :disabled="disabled"
             :name="id" :id="id + '_' + choice.value" :value="choice.value"
             :checked="modelValue.includes(choice.value)"
             @change="toggle(choice.value, $event.target.checked)">
      <label class="form-check-label clickable" :for="id + '_' + choice.value">{{ choice.label }}</label>
    </div>
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
    disabled: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    toggle: function(value, checked) {
      const nextValue = this.modelValue.filter(v => v !== value)
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
