<template>
  <div>
    <button-confirm-modal v-if="!probe.closedAt && (missingObservations || missingReport || missingElmReport)"
                          :title="$t('_action.toggle_closed.close')" color="secondary"
                          :confirm-label="$t('_action.toggle_closed.force_close')" :confirm="toggleClosed">
      <div class="d-flex gap-2 flex-column">
        <p v-if="missingObservations" class="alert alert-warning mb-0">
          {{ $t('_action.toggle_closed.missing_observations') }}
        </p>
        <p v-if="missingReport" class="alert alert-warning mb-0">
          {{ $t('_action.toggle_closed.missing_report') }}
        </p>
        <p v-if="missingElmReport" class="alert alert-warning mb-0">
          {{ $t('_action.toggle_closed.missing_elm_report') }}
        </p>
      </div>
    </button-confirm-modal>
    <button v-else class="btn" :class="{'btn-secondary': !recommendAction, 'btn-primary': recommendAction}"
            @click="toggleClosed" :disabled="isLoading">
    <span class="d-flex gap-3 align-items-center">
          <looping-rhombus-spinner v-if="isLoading" class="white"/>
          {{ probe.closedAt ? $t('_action.toggle_closed.open') : $t('_action.toggle_closed.close') }}
    </span>
    </button>
  </div>
</template>

<script>

import {displaySuccess} from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import moment from "moment/moment";
import {api} from "../../domain/api";
import ButtonConfirmModal from "../Library/Behaviour/Modal/ButtonConfirmModal.vue";

export default {
  emits: ['closed'],
  components: {
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
    missingObservations: {
      type: Boolean,
      required: false
    },
    missingReport: {
      type: Boolean,
      required: false
    },
    missingElmReport: {
      type: Boolean,
      required: false
    }
  },
  computed: {
    recommendAction: function () {
      return !this.probe.closedAt && !this.missingObservations && !this.missingReport && !this.missingElmReport
    }
  },
  data() {
    return {
      isLoading: false
    }
  },
  methods: {
    toggleClosed: async function () {
      this.isLoading = true;
      const patch = {closedAt: this.probe.closedAt ? null : new Date().toISOString()}
      if (!this.probe.analysisStopDate) {
        patch['analysisStopDate'] = new Date().toISOString().substring(0, 10) // YYYY-mm-dd
      }

      await api.patch(this.probe, patch)
      this.isLoading = false
      this.$emit('closed')

      const successMessage = this.probe.closedAt ? this.$t('_action.toggle_closed.closed') : this.$t('_action.toggle_closed.opened')
      displaySuccess(successMessage)
    },
  }
}
</script>
