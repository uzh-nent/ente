<template>
  <div>
    <div class="form-check form-check-inline" v-for="choice in choices" :key="choice.value">
      <input class="form-check-input" type="radio" :required="required" :disabled="disabled"
             :name="id" :id="id + '_' + choice.value" :value="choice.value"
             :checked="choice.value === modelValue"
             @change="$event.target.checked ? $emit('update:modelValue', choice.value) : null">
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
    choices: {
      type: Array,
      required: true
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    required: function () {
      return this.field?.rules.includes(requiredRule)
    }
  }
}
</script>

<style scoped>
.clickable {
  cursor: pointer;
}
</style>
