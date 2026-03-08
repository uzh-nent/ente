<template>
  <button-confirm-modal
      :title="$t('_action.remove_invoice.title')" icon="fas fa-trash"
      button-size="sm" color="danger"
      :confirm-label="$t('_action.remove')" :confirm="confirm">
    <p class="alert alert-warning">
      {{ $t("_action.remove_invoice.help")}}
    </p>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'

export default {
  emits: ['removed'],
  components: {
    ButtonConfirmModal,
  },
  props: {
    invoice: {
      type: Object,
      required: true
    },
  },
  methods: {
    confirm: async function () {
      const successMessage = this.$t('_action.remove_invoice.removed')

      await api.delete(this.invoice)
      displaySuccess(successMessage)

      this.$emit('removed')
    },
  },
}
</script>
