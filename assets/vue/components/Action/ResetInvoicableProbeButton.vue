<template>
  <button class="btn btn-secondary btn-sm" :disabled="loading" @click="reset">
    <i class="fas fa-arrow-rotate-left"></i>
  </button>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'

export default {
  components: {
    ButtonConfirmModal,
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      loading: false
    }
  },
  methods: {
    reset: async function () {
      this.loading = true
      const successMessage = this.$t('_action.reset_invoicable_probe.reset')

      await api.patch(this.probe, {invoiceStatus: null})
      displaySuccess(successMessage)
      this.loading = false
    },
  },
}
</script>
