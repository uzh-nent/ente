
<template>
  <div class="d-flex gap-2 flex-column">
    <div class="form-check bg-light" :class="{'form-check-inline': inline}" v-for="choice in choices" :key="choice.value">
      <div class="p-2">
        <input class="form-check-input" type="checkbox" :required="required" :disabled="disabled"
               :name="id" :id="id + '_' + valueString(choice.value)" :value="choice.value"
               :checked="isChecked(choice.value)"
               @change="handleChange(choice.value, $event.target.checked)">
        <label class="form-check-label clickable whitespace-preserve-newlines" :for="id + '_' + valueString(choice.value)">{{ choice.label }}</label>
      </div>
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
      type: [Array],
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
    },
  },
  computed: {
    required: function () {
      return this.field?.rules.includes(requiredRule)
    }
  },
  methods: {
    isChecked: function (choiceValue) {
      if (this.modelValue) {
        return this.modelValue.includes(choiceValue)
      }
      return false
    },
    handleChange:function (choiceValue, isChecked) {
      const newValue = (this.modelValue ?? []).filter(v => v !== choiceValue)

      if (isChecked) {
          newValue.push(choiceValue)
      }

      this.$emit('update:modelValue', newValue)
    }
  }
}
</script>

<style scoped>
.clickable {
  cursor: pointer;
}
</style>
