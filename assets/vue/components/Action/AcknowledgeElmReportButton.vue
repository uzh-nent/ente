<template>
  <button class="btn btn-secondary"
          @click="toggleAcknowledged" :disabled="isLoading">
    <span class="d-flex gap-3 align-items-center">
        <looping-rhombus-spinner v-if="isLoading" class="white"/>
        <i v-else :class="elmReport.acknowledgedAt ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
    </span>
  </button>
</template>

<script>

import {displaySuccess} from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import {api} from "../../domain/api";

export default {
  emits: ['acknowledged'],
  components: {
    LoopingRhombusSpinner,
  },
  props: {
    elmReport: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      isLoading: false
    }
  },
  methods: {
    toggleAcknowledged: async function () {
      this.isLoading = true;
      const patch = {acknowledgedAt: this.elmReport.acknowledgedAt ? null : new Date().toISOString()}

      await api.patch(this.elmReport, patch)
      this.isLoading = false
      this.$emit('acknowledged')

      const successMessage = this.elmReport.acknowledgedAt ? this.$t('_action.acknowledge_elm_report.acknowledged') : this.$t('_action.acknowledge_elm_report.not_acknowledged')
      displaySuccess(successMessage)
    },
  }
}
</script>
