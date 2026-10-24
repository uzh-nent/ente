<template>
  <button class="btn" :class="{'btn-secondary': !recommendClosing, 'btn-primary': recommendClosing}"
          @click="togglClosed" :disabled="isLoading">
    <span class="d-flex gap-3 align-items-center">
          <looping-rhombus-spinner v-if="isLoading" class="white"/>
          {{ probe.closedAt ? $t('_action.toggle_closed.open') : $t('_action.toggle_closed.close') }}
    </span>
  </button>
</template>

<script>

import {displaySuccess} from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import moment from "moment/moment";
import {api} from "../../domain/api";

export default {
  emits: ['closed'],
  components: {
    LoopingRhombusSpinner,
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
    hasReports: {
      type: Boolean,
      required: false
    }
  },
  data() {
    return {
      isLoading: false
    }
  },
  computed: {
    recommendClosing: function () {
      return !this.probe.closedAt && this.hasReports
    }
  },
  methods: {
    togglClosed: async function () {
      this.isLoading = true;
      const patch = {closedAt: this.probe.closedAt ? null : moment().format()}
      await api.patch(this.probe, patch)
      this.isLoading = false
      this.$emit('closed')

      const successMessage = this.probe.closedAt ? this.$t('_action.toggle_closed.opened') : this.$t('_action.toggle_closed.closed')
      displaySuccess(successMessage)
    },
  }
}
</script>
