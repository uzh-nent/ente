<template>
  <div>
    <labeled-value :label="$t('invoice.date')">
      {{ formatDate(invoice.date) }}
    </labeled-value>
    <labeled-value :label="$t('invoice.receiver')">
      {{ $t(`invoice._receiver.${invoice.receiver}`) }}
    </labeled-value>
    <labeled-value class="mt-3" :label="$t('invoice.address')">
      <span class="whitespace-preserve-newlines">
        {{ invoice.address }}
      </span>
    </labeled-value>
    <labeled-value class="mt-3" :label="$t('invoice.invoice_identifier')">
      {{ invoice.invoiceIdentifier }}
    </labeled-value>
    <hr/>
    <labeled-value :label="$t('invoice.created')">
      {{ formatDateTime(invoice.createdAt) }} / {{ invoicedBy?.abbreviation }}
    </labeled-value>
  </div>
</template>

<script>
import LabeledValue from "../Library/View/LabeledValue.vue";
import {formatDate, formatDateTime} from "../../services/domain/formatter";

export default {
  methods: {formatDate, formatDateTime},
  components: {LabeledValue},
  props: {
    invoice: {
      type: Object,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
  },
  computed: {
    invoicedBy: function () {
      return this.users.find(u => u['@id'] === this.invoice.createdBy)
    }
  }
}

</script>
