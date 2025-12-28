<template>
  <div>
    <text-input v-if="searchEnabled" class="mb-2" id="searchChoice"
                type="text" :placeholder="$t('_form.search')"
                v-model="searchChoice" />
    <select
        :id="id" class="form-control" :required="required" :multiple="multiple" :disabled="disabled"
        :class="{'is-valid': field?.valid, 'is-invalid': field?.invalid }"
        v-model="internalModelValue"
        @blur="$emit('blur')">
      <option :value="null" v-if="!required && !multiple">&mdash;</option>
      <option v-for="choice in shownChoices" :key="choice.value" :value="choice.value">
        {{ choice.label }}
      </option>
      <slot/>
    </select>
    <span v-if="searchEnabled" class="form-text">{{ itemHits }}</span>
  </div>
</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import {requiredRule} from '../../Form/utils/form'
import {searchableChoices} from "./utils/search";
import TextInput from "./TextInput.vue";

export default {
  components: {TextInput, InvalidFeedback},
  emits: ['blur', 'update:modelValue'],
  mixins: [searchableChoices],
  data() {
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
    choices: {
      type: Array,
      required: true
    },
    multiple: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
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
