<template>
  <button-confirm-modal
      :title="$t('_action.add_report.title')" icon="fas fa-plus"
      :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusAddressAdd">
    <report-meta-form :template="reportMetaTemplate" :probe="probe" @update="reportMeta = $event"/>

    <hr/>
    <div class="d-flex flex-column gap-2">
      <div v-for="address in predefinedAddresses" :key="address" class="bg-light p-2 rounded d-flex">
        {{ address.replace("\n", ", ") }}
      </div>
      <add-address-button
          :label="$t('_action.add_report.add_copy_to')" class="align-self-start mt-2"
                          ref="addAddressButton" @add="copyToAddresses.push($event)"/>
      <div v-for="address in copyToAddresses" :key="address" class="bg-light p-2 rounded d-flex">
        <div class="flex-grow-1">
          {{ address.replace("\n", ", ") }}
        </div>
        <div class="ms-2">
          <button class="btn btn-sm btn-outline-danger"
                  @click="copyToAddresses.splice(copyToAddresses.indexOf(address), 1)">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    </div>

    <hr/>

    <div class="d-flex flex-column gap-3">
      <div v-for="(resultTemplate, i) in resultTemplates" :key="i" class="mt-3 p-2 bg-light">
        <checkbox
            :id="'toggle-' + i" :label="resultTemplate.analysis"
            :model-value="shownResults.includes(i)" @update:modelValue="toggleShownResults(i, $event)"
        />
        <report-result-form class="mt-2" v-if="shownResults.includes(i)" :template="resultTemplate"
                            @update="results[i] = $event"/>
      </div>
    </div>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ElmReportForm from "../Form/ElmReportForm.vue";
import ReportMetaForm from "../Form/ReportMetaForm.vue";
import moment from "moment";
import AddAddressButton from "./AddAddressButton.vue";
import {createResults} from "../../services/domain/report";
import ReportResultForm from "../Form/ReportResultForm.vue";
import {probeConverter} from "../../services/domain/converters";
import {formatOrganizationAddress, formatPractitionerAddress} from "../../services/domain/formatter";
import Checkbox from "../Library/FormInput/Checkbox.vue";

export default {
  emits: ['added'],
  components: {
    Checkbox,
    ReportResultForm,
    AddAddressButton,
    ReportMetaForm,
    ElmReportForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      reportMeta: null,
      copyToAddresses: [],
      results: [],
      shownResults: []
    }
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
    observations: {
      type: Array,
      required: true
    },
    reports: {
      type: Array,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return this.reportMeta && this.results.length > 0
    },
    reportMetaTemplate: function () {
      const template = {date: moment().format('YYYY-MM-DD'), claimCertification: true}
      if (this.probe.analysisTypes.length > this.observations.length) {
        return {...template, predefinedTitle: 'INTERMEDIATE'}
      } else if (this.reports.length === 0) {
        return {...template, predefinedTitle: 'FINAL'}
      } else {
        return {...template, predefinedTitle: 'ADDENDUM'}
      }
    },
    resultTemplates: function () {
      return createResults(this.probe, this.observations, this.organisms, this.$t)
    },
    predefinedAddresses: function () {
      const addresses = [];
      if (this.probe.ordererOrg) {
        const organization = probeConverter.reconstructOrdererOrgOrganization(this.probe)
        addresses.push(formatOrganizationAddress(organization))
      }

      if (this.probe.ordererPrac) {
        const practitioner = probeConverter.reconstructOrdererPracPractitioner(this.probe)
        addresses.push(formatPractitionerAddress(practitioner))
      }

      return addresses
    },
    payload: function () {
      const payload = {...this.reportMetaTemplate, ...this.reportMeta, probe: this.probe['@id']}

      // normalize title
      if (payload.predefinedTitle) {
        payload.title = this.$t('report._predefined_title.' + payload.predefinedTitle)
      } else {
        payload.title = payload.customTitle
      }
      delete payload.predefinedTitle
      delete payload.customTitle

      // add sublists
      if (this.copyToAddresses.length > 0) {
        payload.copyToAddresses = this.copyToAddresses
      }
      payload.results = this.resultTemplates.map((template, index) => {
        return {
          ...template,
          ...this.results[index]
        }
      }).filter((_, index) => this.shownResults.includes(index))

      return payload
    }
  },
  methods: {
    toggleShownResults: function (i, value) {
      const otherShownResults = this.shownResults.filter(o => o !== i)
      this.shownResults = value ? otherShownResults.concat(i) : otherShownResults
    },
    confirm: async function () {
      const report = await api.postReport(this.payload)
      this.$emit('added', report)

      const successMessage = this.$t('_action.add_report.added')
      displaySuccess(successMessage)
    },
    focusAddressAdd: function () {
      this.$nextTick(() => {
        this.$refs.addAddressButton.$el?.focus()
      })
    }
  },
  mounted() {
    this.copyToAddresses = this.reports.length ? this.reports[this.reports.length - 1].copyToAddresses : []
    this.copyToAddresses = this.copyToAddresses ?? []

    // show all per default
    this.shownResults = this.resultTemplates.map((_, i) => i)
  }
}
</script>
