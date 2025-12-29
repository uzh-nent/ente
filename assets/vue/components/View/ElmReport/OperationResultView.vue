<template>
  <div v-if="operationOutcome" class="accordion" id="validationIssuesAccordion">
    <div
        v-for="(issue, index) in operationOutcome.issue"
        :key="index"
        class="accordion-item">
      <h2 class="accordion-header">
        <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            :data-bs-target="'#issue-' + index"
            aria-expanded="false">
          <i :class="['fas', getIcon(issue.severity), getTextColor(issue.severity), 'me-2']"></i>
          <span class="text-capitalize me-2">{{ issue.severity }}</span>
          <small class="text-muted text-truncate">
            {{ (issue.diagnostics || issue.details?.text) }}
          </small>
        </button>
      </h2>
      <div :id="'issue-' + index"
           class="accordion-collapse collapse"
           data-bs-parent="#validationIssuesAccordion">
        <div class="accordion-body">
          <p class="mb-0">{{ issue.diagnostics || issue.details?.text }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import OrganizationView from "../OrganizationView.vue";
import {probeConverter} from "../../../services/domain/converters";

export default {
  components: {OrganizationView, LabeledValue},
  props: {
    json: {
      type: String,
      required: true
    },
  },
  computed: {
    payload: function () {
      if (!this.json) {
        return null;
      }

      return JSON.parse(this.json)
    },
    operationOutcome: function () {
      if (!this.payload) {
        return null;
      }

      if (this.payload.resourceType === 'OperationOutcome') {
        return this.payload
      }

      if (Array.isArray(this.payload.contained)) {
        return this.payload.contained.find(p => p.resourceType === 'OperationOutcome')
      }

      return null
    },
    issues: function () {

    }
  },
  methods: {
    getIcon: function (severity) {
      return {
        'error': 'fa-circle-xmark',
        'warning': 'fa-triangle-exclamation',
        'information': 'fa-circle-info'
      }[severity] || 'fa-circle-question';
    },
    getTextColor: function (severity) {
      return {
        'error': 'text-danger',
        'warning': 'text-warning',
        'information': 'text-info'
      }[severity] || 'text-secondary';
    }
  }
}

</script>
