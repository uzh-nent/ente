<template>
  <div class="mb-3">
    <label v-if="label" :for="forId">
      {{ label }}
      <span v-if="required" class="text-danger">*</span>
    </label>
    <slot />
    <div v-if="help" class="form-text">{{ help }}</div>
    <invalid-feedback :field="field" />
  </div>
</template>

<script>

import { requiredRule } from '../../Form/utils/form'
import InvalidFeedback from "./InvalidFeedback.vue";

export default {
  components: {InvalidFeedback},
  props: {
    forId: {
      type: String,
      required: true
    },
    label: {
      type: String,
      default: null
    },
    help: {
      type: String,
      default: null
    },
    field: {
      type: Object,
      default: null
    },
    fakeRequired: {
      type: Boolean,
      default: false
    },
  },
  computed: {
    required: function () {
      return this.fakeRequired || this.field?.rules.includes(requiredRule)
    }
  }
}
</script>
