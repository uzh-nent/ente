<template>
  <div>
    <labeled-value width="w-25" :label="$t('report_email.receivers')">
      {{ reportEmail.receivers }}
    </labeled-value>
    <labeled-value width="w-25" :label="$t('report_email.cc_receivers')">
      {{ reportEmail.ccReceivers }}
    </labeled-value>
    <hr/>
    <labeled-value width="w-25" :label="$t('report_email.subject')">
      <b>{{ reportEmail.subject }}</b>
    </labeled-value>
    <labeled-value width="w-25" :label="$t('report_email.body')">
      <span class="whitespace-preserve-newlines">
      {{ reportEmail.body }}
        </span>
    </labeled-value>
    <hr/>
    <labeled-value width="w-25" :label="$t('report_email.sent')">
      <span class="whitespace-preserve-newlines">
      {{ formatDateTime(reportEmail.sentAt) }} / {{ sentBy?.abbreviation }}
      </span>
    </labeled-value>
  </div>
</template>

<script>
import LabeledValue from "../Library/View/LabeledValue.vue";
import {formatDateTime} from "../../services/domain/formatter";

export default {
  methods: {formatDateTime},
  components: {LabeledValue},
  props: {
    reportEmail: {
      type: Object,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
  },
  computed: {
    sentBy: function () {
      return this.users.find(u => u['@id'] === this.reportEmail.createdBy)
    }
  }
}

</script>
