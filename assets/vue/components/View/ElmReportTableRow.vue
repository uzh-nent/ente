<template>
  <tr>
    <td>
      <attribution-view :users="users" :entity="report"/>
    </td>
    <td>
      {{ leadingCode.displayName }}
      <template v-if="organism">
        <br>{{ organism.displayName }}
      </template>
      <template v-if="report.organismText">
        <br>{{ report.organismText }}
      </template>
    </td>
    <td>
      <template v-if="report.apiStatus === 'VALIDATION_ERROR'">
        <i class="fas fa-xmark-circle"></i>
        {{ $t('elm_report.api_status.technical_error') }}
      </template>
      <template v-else-if="report.apiStatus === 'VALIDATION_ISSUES'">
        <i class="fas fa-xmark-circle"></i>
        {{ $t('elm_report.api_status.validation_error') }}
      </template>
      <template v-else>
        <i class="fas fa-check-circle"></i>

        <template v-if="report.apiStatus === 'SEND_ERROR'">
          <i class="fas fa-xmark-circle"></i>
          {{ $t('elm_report.api_status.technical_error') }}
        </template>
        <template v-else-if="report.apiStatus === 'SEND_ISSUES'">
          <i class="fas fa-xmark-circle"></i>
          {{ $t('elm_report.api_status.validation_error') }}
        </template>
        <template v-else>
          <i class="fas fa-check-circle"></i>

          <template v-if="report.apiStatus === 'QUEUED'">
            <duck-walking/>
            {{ $t('elm_report.api_status.queued') }}
          </template>
          <template v-else-if="report.apiStatus === 'COMPLETED'">
            <i class="fas fa-check-circle"></i>
            {{ $t('elm_report.api_status.completed') }}
          </template>
          <template v-else>
            <i class="fas fa-xmark-circle"></i>
            {{ $t('elm_report.api_status.validation_error') }}
          </template>

          {{ report.documentReferenceId }}
        </template>
      </template>
    </td>
  </tr>
</template>

<script>
import AttributionView from "./AttributionView.vue";
import DuckWalking from "../Library/View/Base/DuckWalking.vue";

export default {
  components: {DuckWalking, AttributionView},
  props: {
    report: {
      type: Object,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
    leadingCodes: {
      type: Array,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  },
  computed: {
    leadingCode: function () {
      return this.leadingCodes.find(lc => lc['@id'] === this.report.leadingCode)
    },
    organism: function () {
      return this.organisms.find(o => o['@id'] === this.report.organism)
    }
  }
}

</script>

