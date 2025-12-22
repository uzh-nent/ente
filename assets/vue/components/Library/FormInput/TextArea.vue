<template>
  <textarea
    :id="id" class="form-control" :rows="rows" :required="required"
    :class="{'is-valid': field?.valid, 'is-invalid': field?.invalid }"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    @blur="$emit('blur')"
   />
  <invalid-feedback :field="field" />
</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import { requiredRule } from '../../Form/utils/form'

export default {
  components: { InvalidFeedback },
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
    field: {
      type: Object,
      default: null
    },
    rows: {
      type: Number,
      default: 4
    }
  },
  computed: {
    required: function () {
      return this.field?.rules.includes(requiredRule)
    }
  }
}
</script>
