<template>
  <div class="position-relative">
    <textarea
        :id="id" class="form-control" :rows="rows" :required="required"
        :class="{'is-valid': field?.valid, 'is-invalid': field?.invalid }"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        @blur="$emit('blur')"
    />
    <div class="position-absolute bottom-0 end-0 mb-2 me-2" v-if="standardTexts.length > 0" >
      <add-standard-text-button :standard-texts="standardTexts" @add="addText($event)"/>
    </div>
  </div>

</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import {requiredRule} from '../../Form/utils/form'
import AddStandardTextButton from "../../Action/AddStandardTextButton.vue";

export default {
  components: {AddStandardTextButton, InvalidFeedback},
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
      default: 6
    },
    standardTexts: {
      type: Array,
      default: []
    }
  },
  computed: {
    required: function () {
      return this.field?.rules.includes(requiredRule)
    }
  },
  methods: {
    addText: function (text) {
      if (!this.modelValue) {
        this.$emit('update:modelValue', text)
        return
      }

      if (!this.modelValue.endsWith("\n")) {
        this.$emit('update:modelValue', this.modelValue + "\n" + text)
      } else {
        this.$emit('update:modelValue', this.modelValue + text)
      }
    }
  }
}
</script>
