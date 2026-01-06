<template>
  <button-confirm-modal
      :title="$t('_action.add_report.title')" icon="fas fa-plus"
      :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusAddressAdd">
    <report-meta-form :template="reportMetaTemplate" :probe="probe" @update="reportMeta = $event"/>

    <hr/>
    <div class="d-flex gap-2">
      <button v-if="canIncludeOrdererOrgAddress" class="btn btn-primary" @click="addresses.push(ordererOrgAddress)">
        <i class="fas fa-plus"></i>
        {{ $t("_action.add_report.add_orderer_org") }}
      </button>
      <add-address-button ref="addAddressButton" @add="addresses.push($event)"/>
    </div>
    <div class="d-flex flex-column gap-2 mt-2" v-if="addresses.length > 0">
      <div v-for="address in addresses" :key="address" class="bg-light p-2 rounded d-flex">
        <div class="flex-grow-1 items-center">
          {{ address.replace("\n", ", ") }}
        </div>
        <div class="ms-2">
          <button class="btn btn-sm btn-outline-danger" @click="addresses.splice(addresses.indexOf(address), 1)">
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
        <report-result-form class="mt-2" v-if="shownResults.includes(i)" :template="resultTemplate" @update="results[i] = $event"/>
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
import {formatOrganizationAddress} from "../../services/domain/formatter";
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
      addresses: [],
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
      return this.reportMeta && this.results.length > 0 && this.addresses.length > 0
    },
    ordererOrgAddress: function () {
      if (!this.probe.ordererOrg) {
        return null
      }

      const organization = probeConverter.reconstructOrdererOrgOrganization(this.probe)
      return formatOrganizationAddress(organization)
    },
    canIncludeOrdererOrgAddress: function () {
      return this.ordererOrgAddress && !this.addresses.includes(this.ordererOrgAddress)
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
      payload.addresses = this.addresses
      payload.results = this.resultTemplates.map((template, index) => {
        return {
          ...template,
          ...this.results[index]
        }
      })

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
    this.addresses = this.reports.length ? this.reports[this.reports.length - 1].addresses : []
    this.addresses = this.addresses ?? []

    if (this.canIncludeOrdererOrgAddress) {
      this.addresses.push(this.ordererOrgAddress)
    }

    // show all per default
    this.shownResults = this.resultTemplates.map((_, i) => i)
  }
}
</script>
