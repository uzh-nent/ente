<template>
  <label-modal v-if="show"
               :icon="icon" :label="showLabel ? label : null" :label-color="labelColor"
               :title="$t('_action.view_api_status.title')">
    <template v-slot:label v-if="status === 'in_queue'">
      <duck-walking/>
    </template>

    <labeled-value :label="$t('elm_report.request')">
      <a :href="requestDownloadUrl" :download="requestFilename">
        <i class="fas fa-download"/>
        {{ $t('messages.download') }}
      </a>
    </labeled-value>

    <labeled-value :label="$t('elm_report.step')">
      {{ $t('elm_report._step.' + step) }}
    </labeled-value>

    <labeled-value :label="$t('elm_report.status')">
      <span :class="'text-' + labelColor">
        <i v-if="icon" :class="icon"/>
        <duck-walking v-if="status === 'in_queue'"/>
        {{ label }}
      </span>
    </labeled-value>

    <div class="mt-2">
      <a v-if="stepResponseDownloadUrl" :href="stepResponseDownloadUrl" :download="stepResponseFilename">
        <i class="fas fa-download"/>
        {{ $t('_action.view_api_status.download_response') }}
      </a>
    </div>
  </label-modal>
</template>

<script>
import LabeledValue from "../Library/View/LabeledValue.vue";
import LabelModal from "../Library/Behaviour/Modal/LabelModal.vue";
import DuckWalking from "../Library/View/Base/DuckWalking.vue";

export default {
  components: {DuckWalking, LabelModal, LabeledValue},
  props: {
    report: {
      type: Object,
      required: false
    },
    step: {
      type: String,
      required: true,
      validator: value => ['validation', 'send', 'queue'].includes(value)
    },
  },
  computed: {
    status: function () {
      if (this.step === 'validation') {
        if (this.report.apiStatus === 'VALIDATION_ERROR') {
          return 'technical_error'
        } else if (this.report.apiStatus === 'VALIDATION_ISSUES') {
          return 'validation_error'
        } else {
          return 'success';
        }
      } else if (this.step === 'send') {
        if (this.report.apiStatus === 'SEND_ERROR') {
          return 'technical_error'
        } else if (this.report.apiStatus === 'SEND_ISSUES') {
          return 'validation_error'
        } else {
          return 'success';
        }
      } else if (this.step === 'queue') {
        if (this.report.apiStatus === 'QUEUED') {
          return 'in_queue'
        } else if (this.report.apiStatus === 'COMPLETED') {
          return 'success';
        } else {
          return 'validation_error'
        }
      }

      return null
    },
    show: function () {
      if (this.step === 'validation') {
        return true;
      }

      if (this.step === 'send' && !this.report.apiStatus.startsWith('VALIDATION')) {
        return true;
      }

      if (this.step === 'queue' && !this.report.apiStatus.startsWith('VALIDATION') && !this.report.apiStatus.startsWith('SEND')) {
        return true;
      }

      return false
    },
    showLabel: function () {
      if (this.step === 'validation' && this.report.apiStatus.startsWith('VALIDATION')) {
        return true;
      }

      if (this.step === 'send' && this.report.apiStatus.startsWith('SEND')) {
        return true;
      }

      if (this.step === 'queue') {
        return true;
      }

      return false
    },
    label: function () {
      return this.$t('elm_report._status.' + this.status)
    },
    labelColor: function () {
      if (this.status === 'success') {
        return 'success'
      }

      if (this.status === 'technical_error' || this.status === 'validation_error') {
        return 'danger'
      }

      return null
    },
    icon: function () {
      if (this.status === 'technical_error' || this.status === 'validation_error') {
        return "fas fa-xmark-circle"
      } else if (this.status === 'success') {
        return "fas fa-check-circle"
      }

      return null
    },
    requestDownloadUrl: function () {
      if (!this.report.requestJson) {
        return null;
      }

      const blob = new Blob([this.report.requestJson], {type: 'application/json'});
      return window.URL.createObjectURL(blob);
    },
    requestFilename: function () {
      return `${this.report.diagnosticReportId}.json`
    },
    stepResponseDownloadUrl: function () {
      let stepResponseJson
      if (this.step === 'validation') {
        stepResponseJson = this.report.validationResponseJson
      } else if (this.step === 'send') {
        stepResponseJson = this.report.sendResponseJson
      } else {
        stepResponseJson = this.report.lastDocumentReferenceResponseJson
      }
      if (!stepResponseJson) {
        return null;
      }

      const blob = new Blob([stepResponseJson], {type: 'application/json'});
      return window.URL.createObjectURL(blob);
    },
    stepResponseFilename: function () {
      return `${this.report.diagnosticReportId}-reponse-${this.step}.json`
    }
  }
}

</script>
