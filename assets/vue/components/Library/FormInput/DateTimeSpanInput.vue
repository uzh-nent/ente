<template>
  <div class="d-flex gap-2">
    <div class="input-group">
      <div class="input-group-text">{{ $t('_view.from') }}</div>
      <date-time-input
          :id="id + '[after]'" :format="format" :modelValue="after"
          @update:modelValue="$emit('update:after', $event)"/>
    </div>
    <div class="input-group">
      <div class="input-group-text">{{ $t('_view.until') }}</div>
      <date-time-input
          :id="id + '[before]'" :format="format" :modelValue="before"
          @update:modelValue="$emit('update:before', $event)"
      />
    </div>
  </div>
</template>

<script>

import InvalidFeedback from '../FormLayout/InvalidFeedback.vue'
import {requiredRule} from '../../Form/utils/form'
import {toggleAnchorValidity, flatPickr, dateTimeConfig, dateConfig} from '../../../services/flatpickr'
import DateTimeInput from "./DateTimeInput.vue";

export default {
  components: {DateTimeInput, InvalidFeedback, flatPickr},
  emits: ['blur', 'update:after', 'update:before'],
  props: {
    after: {
      type: String,
      default: null
    },
    before: {
      type: String,
      default: null
    },
    id: {
      type: String,
      required: true
    },
    format: {
      type: String,
      default: 'datetime'
    }
  },
}
</script>
