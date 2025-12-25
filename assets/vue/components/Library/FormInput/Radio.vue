<template>
  <div>
    <div class="form-check" :class="{'form-check-inline': inline}" v-for="choice in choices" :key="choice.value">
      <input class="form-check-input" type="radio" :required="required" :disabled="disabled"
             :name="id" :id="id + '_' + valueString(choice.value)" :value="choice.value"
             :checked="choice.value === modelValue"
             @change="$event.target.checked ? $emit('update:modelValue', choice.value) : null">
      <label class="form-check-label clickable" :for="id + '_' + valueString(choice.value)">{{ choice.label }}</label>
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
      type: [String, Object, Array],
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
    inline: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    valueString: {
      type: Function,
      default: (value) => value
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
