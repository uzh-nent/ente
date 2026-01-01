<template>
  <button class="btn" :class="{'btn-secondary': !recommendFinishing, 'btn-primary': recommendFinishing}"
          @click="toggleFinish" :disabled="isLoading">
    <span class="d-flex gap-3 align-items-center">
          <looping-rhombus-spinner v-if="isLoading" class="white"/>
          {{ probe.finishedAt ? $t('_action.toggle_finished.open') : $t('_action.toggle_finished.finish') }}
    </span>
  </button>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import AnimalKeeperForm from "../Form/AnimalKeeperForm.vue";
import moment from "moment/moment";

export default {
  emits: ['finished'],
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
    recommendFinishing: function () {
      return !this.probe.finishedAt && this.hasReports
    }
  },
  methods: {
    toggleFinish: async function () {
      this.isLoading = true;
      const patch = {finishedAt: this.probe.finishedAt ? null : moment().format()}
      await api.patch(this.probe, patch)
      this.isLoading = false
      this.$emit('finished')

      const successMessage = this.probe.finishedAt ? this.$t('_action.toggle_finished.opened') : this.$t('_action.toggle_finished.finished')
      displaySuccess(successMessage)
    },
  }
}
</script>
