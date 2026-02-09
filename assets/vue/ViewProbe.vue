<template>
  <div class="row" v-if="probe">
    <div class="col-lg-4">
      <h3>{{ $t('probe.service_request') }}</h3>
      <actionable-card>
        <service-request-view :probe="probe"/>
        <template v-slot:actions v-if="!probe.finishedAt">
          <edit-probe-service-request-button :probe="probe"/>
        </template>
      </actionable-card>

      <h3 class="mt-5">{{ $t('probe.orderer') }}</h3>
      <actionable-card>
        <orderer-view :probe="probe"/>
        <template v-slot:actions v-if="!probe.finishedAt">
          <edit-probe-orderer-button :probe="probe"/>
        </template>
      </actionable-card>

      <h3 class="mt-5">{{ $t('probe._name') }}</h3>
      <actionable-card>
        <specimen-meta-view :probe="probe" :specimens="specimens"/>
        <template v-slot:actions v-if="!probe.finishedAt">
          <edit-probe-specimen-meta-button :specimens="specimens" :probe="probe"/>
        </template>
      </actionable-card>

      <template v-if="probe.specimenSource === 'HUMAN'">
        <h3 class="mt-5">{{ $t('patient._name') }}</h3>
        <actionable-card>
          <patient-view :probe="probe"/>
          <template v-slot:actions v-if="!probe.finishedAt">
            <edit-probe-patient-button :probe="probe"/>
          </template>
        </actionable-card>
      </template>

      <template v-if="probe.specimenSource === 'ANIMAL'">
        <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
        <actionable-card>
          <animal-view :probe="probe"/>
          <template v-slot:actions v-if="!probe.finishedAt">
            <edit-probe-animal-button :probe="probe"/>
          </template>
        </actionable-card>
      </template>
    </div>
    <div class="col-lg-8">
      <h3>{{ $t('probe.progress') }}</h3>
      <actionable-card class="w-50">
        <service-time-view :probe="probe" :users="users"/>
        <template v-slot:actions v-if="!probe.finishedAt">
          <edit-probe-service-time-button :probe="probe"/>
        </template>
      </actionable-card>

      <download-probe-worksheet-button class="mt-5" :probe="probe" :has-observations="observations.length > 0"/>

      <div class="mt-5">
        <h3>{{ $t('observation._name') }}</h3>
        <add-observations-button
            v-if="missingObservations.length > 0" class="mt-2" ref="addObservationsButton"
            :probe="probe" :organisms="organisms" :missing-analysis-types="missingObservations"
            @added="observations.push($event)"
        />
        <observation-table
            v-if="observations.length > 0" class="mt-2"
            :users="users" :observations="observations" :probe="probe" :organisms="organisms"/>

        <div class="mt-5"
             v-if="probe.patient && observations.length > 0 && (!probe.finishedAt || elmReports.length > 0)">
          <h3>{{ $t('elm_report._name') }}</h3>
          <tooltip-wrap :title="$t('_view.no_medical_validation')" :show="!hasMedicalValidation">
            <add-elm-report-button
                v-if="!probe.finishedAt" :disabled="!hasMedicalValidation"
                :probe="probe" :observations="observations"
                :leading-codes="leadingCodes" :organisms="organisms" :specimens="specimens"
                @added="elmReports.push($event)"
            />
          </tooltip-wrap>
          <elm-report-table
              class="mt-2"
              v-if="elmReports.length > 0" :reports="elmReports"
              :users="users" :organisms="organisms" :leading-codes="leadingCodes"/>
        </div>

        <div class="mt-5" v-if="observations.length > 0 && (!probe.finishedAt || reports.length > 0)">
          <h3>{{ $t('report._name') }}</h3>
          <tooltip-wrap :title="$t('_view.no_medical_validation')" :show="!hasMedicalValidation">
            <add-report-button
                v-if="!probe.finishedAt" :disabled="!hasMedicalValidation"
                :probe="probe" :observations="observations" :reports="reports"
                :leading-codes="leadingCodes" :organisms="organisms" :specimens="specimens"
                :standard-texts="standardTexts"
                @added="addedReport($event)"
            />
          </tooltip-wrap>
          <report-table class="mt-2" :users="users" :probe="probe" :reports="reports"/>
        </div>

        <div class="mt-5" v-if="missingObservations.length === 0">
          <toggle-finished-button :probe="probe" :has-reports="reports.length > 0"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import {preloadApi} from './services/api'
import ServiceRequestView from "./components/View/Probe/ServiceRequestView.vue";
import PatientView from "./components/View/Probe/PatientView.vue";
import AnimalView from "./components/View/Probe/AnimalView.vue";
import EditProbeServiceRequestButton from "./components/Action/EditProbeServiceRequestButton.vue";
import EditProbePatientButton from "./components/Action/EditProbePatientButton.vue";
import EditProbeAnimalButton from "./components/Action/EditProbeAnimalButton.vue";
import SpecimenMetaView from "./components/View/Probe/SpecimenMetaView.vue";
import EditProbeSpecimenMetaButton from "./components/Action/EditProbeSpecimenMetaButton.vue";
import ServiceTimeForm from "./components/Form/Probe/ServiceTimeForm.vue";
import ServiceTimeView from "./components/View/Probe/ServiceTimeView.vue";
import EditProbeServiceTimeButton from "./components/Action/EditProbeServiceTimeButton.vue";
import OrdererPracView from "./components/View/Probe/OrdererPracView.vue";
import EditProbeOrdererButton from "./components/Action/EditProbeOrdererButton.vue";
import AddElmReportButton from "./components/Action/AddElmReportButton.vue";
import AttributionView from "./components/View/AttributionView.vue";
import ElmReportTable from "./components/View/ElmReportTable.vue";
import ToggleFinishedButton from "./components/Action/ToggleFinishedButton.vue";
import DownloadProbeWorksheetButton from "./components/Action/DownloadProbeWorksheetButton.vue";
import AddReportButton from "./components/Action/AddReportButton.vue";
import ReportTable from "./components/View/ReportTable.vue";
import AddObservationsButton from "./components/Action/AddObservationsButton.vue";
import ObservationTableRow from "./components/View/ObservationTableRow.vue";
import ObservationTable from "./components/View/ObservationTable.vue";
import TooltipWrap from "./components/Library/View/TooltipWrap.vue";
import OrdererView from "./components/View/Probe/OrdererView.vue";
import ActionableCard from "./components/Library/View/ActionableCard.vue";

export default {
  emits: ['added'],
  components: {
    ActionableCard,
    AnimalView,
    OrdererView,
    TooltipWrap,
    ObservationTable,
    ObservationTableRow,
    AddObservationsButton,
    ReportTable,
    AddReportButton,
    DownloadProbeWorksheetButton,
    EditProbeAnimalButton,
    ToggleFinishedButton,
    ElmReportTable,
    AttributionView,
    AddElmReportButton,
    EditProbeOrdererButton,
    OrdererPracView,
    EditProbeServiceTimeButton,
    ServiceTimeView,
    ServiceTimeForm,
    EditProbeSpecimenMetaButton,
    SpecimenMetaView,
    EditProbePatientButton,
    EditProbeServiceRequestButton,
    PatientView,
    ServiceRequestView,
  },
  data() {
    return {
      probe: undefined,

      observations: undefined,
      elmReports: undefined,
      reports: undefined,

      specimens: undefined,
      leadingCodes: undefined,
      organisms: undefined,

      users: undefined,
      standardTexts: undefined,
    }
  },
  computed: {
    missingObservations: function () {
      return this.probe.analysisTypes.filter(at =>
          !this.observations.some(o => this.probe.pathogen == o.pathogen && this.probe.pathogenName == o.pathogenName && o.analysisType === at)
      )
    },
    hasMedicalValidation: function () {
      const shortname = document.getElementById("shortname").textContent
      const user = this.users.find(u => u.shortname === shortname)

      return user?.medicalValidation
    }
  },
  mounted() {
    const {
      probe,
      observations,
      elmReports,
      reports,
      specimens,
      leadingCodes,
      organisms,
      users,
      standardTexts,
    } = preloadApi.getViewActiveProbe()
    this.probe = probe

    this.observations = observations
    this.elmReports = elmReports
    this.reports = reports

    this.specimens = specimens
    this.leadingCodes = leadingCodes
    this.organisms = organisms

    this.users = users
    this.standardTexts = standardTexts

    if (this.missingObservations.length > 0) {
      this.$nextTick(() => {
        this.$refs.addObservationsButton.$el?.focus()
      })
    }
  },
  methods: {
    addedReport: function (report) {
      this.reports.push(report)
      this.$nextTick(() => {
        document.getElementById('download-report-' + report['@id'])?.click()
      })
    }
  }
}
</script>
