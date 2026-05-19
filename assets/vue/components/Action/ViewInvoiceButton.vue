<template>
  <button-modal
      :title="$t('_action.view_invoice.title')" :title-context="probe.identifier"
      icon="fas fa-file-invoice" button-size="sm" color="success">
    <invoice-view :invoice="invoice" :users="users"/>
  </button-modal>
</template>

<script>

import ButtonModal from "../Library/Behaviour/Modal/ButtonModal.vue";
import InvoiceView from "../View/InvoiceView.vue";

export default {
  emits: ['added'],
  components: {
    InvoiceView,
    ButtonModal,
  },
  props: {
    probe: {
      type: Object,
      required: true,
    },
    invoice: {
      type: Object,
      required: true,
    },
    users: {
      type: Array,
      required: true
    },
  }
}
</script>
