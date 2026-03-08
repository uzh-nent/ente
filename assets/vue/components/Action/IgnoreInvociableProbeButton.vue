<template>
  <button-confirm-modal
      :title="$t('_action.ignore_invoicable_probe.title')" icon="fas fa-eye-slash"
      button-size="sm"
      :confirm-label="$t('_action.ignore')" :confirm="confirm">
    <p class="alert alert-warning">
      {{ $t("_action.ignore_invoicable_probe.help")}}
    </p>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'

export default {
  emits: ['added'],
  components: {
    ButtonConfirmModal,
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
  methods: {
    confirm: async function () {
      const successMessage = this.$t('_action.ignore_invoicable_probe.ignored')

      await api.patch(this.probe, {invoiceStatus: 'IGNORED'})
      displaySuccess(successMessage)
    },
  },
}
</script>
