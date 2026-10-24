<template>
  <div class="row" v-if="probe">
    <div class="col-lg-4">
      <h3>{{ $t('probe.service_request') }}</h3>
      <actionable-card>
        <service-request-view :probe="probe"/>
        <template v-slot:actions v-if="!probe.closedAt">
          <edit-probe-service-request-button :probe="probe"/>
        </template>
      </actionable-card>

      <h3 class="mt-5">{{ $t('probe.orderer') }}</h3>
      <actionable-card>
        <orderer-view :probe="probe"/>
        <template v-slot:actions v-if="!probe.closedAt">
          <edit-probe-orderer-button :probe="probe"/>
        </template>
      </actionable-card>

      <h3 class="mt-5">{{ $t('probe._name') }}</h3>
      <actionable-card>
        <specimen-meta-view :probe="probe" :specimens="specimens"/>
        <template v-slot:actions v-if="!probe.closedAt">
          <edit-probe-specimen-meta-button :specimens="specimens" :probe="probe"/>
        </template>
      </actionable-card>

      <template v-if="probe.specimenSource === 'HUMAN'">
        <h3 class="mt-5">{{ $t('patient._name') }}</h3>
        <actionable-card>
          <patient-view :probe="probe"/>
          <template v-slot:actions v-if="!probe.closedAt">
            <edit-probe-patient-button :probe="probe"/>
          </template>
        </actionable-card>
      </template>

      <template v-if="probe.specimenSource === 'ANIMAL'">
        <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
        <actionable-card>
          <animal-view :probe="probe"/>
          <template v-slot:actions v-if="!probe.closedAt">
            <edit-probe-animal-button :probe="probe"/>
          </template>
        </actionable-card>
      </template>
    </div>
    <div class="col-lg-8">
      <h3>{{ $t('probe.progress') }}</h3>
      <actionable-card class="w-50">
        <service-time-view :probe="probe" :users="users"/>
        <template v-slot:actions v-if="!probe.closedAt">
          <div class="d-flex flex-column gap-2">
            <edit-probe-service-time-button :probe="probe"/>
            <remove-probe-button v-if="isFreshProbe" :probe="probe" @removed="removedProbe"/>
          </div>
        </template>
      </actionable-card>

      <download-probe-worksheet-button
          v-if="!probe.closedAt"
          class="mt-5" ref="downloadWorksheetButton"
          :probe="probe" :has-observations="observations.length > 0"/>

      <div class="mt-5">
        <h3>{{ $t('observation._name') }}</h3>
        <add-observations-button
            v-if="missingObservations.length > 0" class="mt-2" ref="addObservationsButton"
            :probe="probe" :organisms="organisms" :missing-analysis-types="missingObservations"
            :interpretation-texts="interpretationTexts"
            @added="observations.push($event)"
        />
        <observation-table
            v-if="observations.length > 0" class="mt-2"
            :users="users" :observations="observations" :probe="probe" :organisms="organisms"
            :readonly="!!probe.closedAt"
            :interpretation-texts="interpretationTexts"/>

        <div class="mt-5"
             v-if="probe.patient && observations.length > 0 && (!probe.closedAt || elmReports.length > 0)">
          <h3>{{ $t('elm_report._name') }}</h3>
          <tooltip-wrap :title="$t('_view.no_medical_validation')" :show="!hasMedicalValidation">
            <add-elm-report-button
                v-if="!probe.closedAt" :disabled="!hasMedicalValidation"
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

        <div class="mt-5" v-if="observations.length > 0 && (!probe.closedAt || reports.length > 0)">
          <h3>{{ $t('report._name') }}</h3>
          <tooltip-wrap :title="$t('_view.no_medical_validation')" :show="!hasMedicalValidation">
            <add-report-button
                v-if="!probe.closedAt" :disabled="!hasMedicalValidation"
                :probe="probe" :observations="observations" :reports="reports"
                :leading-codes="leadingCodes" :organisms="organisms" :specimens="specimens"
                :report-texts="reportTexts"
                @added="addedReport($event)"
            />
          </tooltip-wrap>
          <report-table class="mt-2" :users="users" :probe="probe" :reports="reports" :report-emails="reportEmails"
                        :readonly="!!probe.closedAt"
                        @sent-email="reportEmails.push($event)"/>
        </div>

        <div class="mt-5" v-if="observations.length > 0">
          <close-probe-button
              :probe="probe"
              :missing-observations="missingObservations.length > 0" :missing-report="reports.length === 0"
              :missing-elm-report="missingElmReport"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import ServiceRequestView from "./components/View/Probe/ServiceRequestView.vue";
import PatientView from "./components/View/Probe/PatientView.vue";
import AnimalView from "./components/View/Probe/AnimalView.vue";
import EditProbeServiceRequestButton from "./components/Action/EditProbeServiceRequestButton.vue";
import EditProbePatientButton from "./components/Action/EditProbePatientButton.vue";
import EditProbeAnimalButton from "./components/Action/EditProbeAnimalButton.vue";
import SpecimenMetaView from "./components/View/Probe/SpecimenMetaView.vue";
import EditProbeSpecimenMetaButton from "./components/Action/EditProbeSpecimenMetaButton.vue";
import ServiceTimeView from "./components/View/Probe/ServiceTimeView.vue";
import EditProbeServiceTimeButton from "./components/Action/EditProbeServiceTimeButton.vue";
import OrdererPracView from "./components/View/Probe/OrdererPracView.vue";
import EditProbeOrdererButton from "./components/Action/EditProbeOrdererButton.vue";
import AddElmReportButton from "./components/Action/AddElmReportButton.vue";
import ElmReportTable from "./components/View/ElmReportTable.vue";
import DownloadProbeWorksheetButton from "./components/Action/DownloadProbeWorksheetButton.vue";
import AddReportButton from "./components/Action/AddReportButton.vue";
import ReportTable from "./components/View/ReportTable.vue";
import AddObservationsButton from "./components/Action/AddObservationsButton.vue";
import ObservationTable from "./components/View/ObservationTable.vue";
import TooltipWrap from "./components/Library/View/TooltipWrap.vue";
import OrdererView from "./components/View/Probe/OrdererView.vue";
import ActionableCard from "./components/Library/View/ActionableCard.vue";
import {checkHasMedicalValidation} from "./domain/authorization";
import {preloadApi, router} from "./domain/api";
import RemoveProbeButton from "./components/Action/RemoveProbeButton.vue";
import CloseProbeButton from "./components/Action/CloseProbeButton.vue";

export default {
  emits: ['added'],
  components: {
    CloseProbeButton,
    RemoveProbeButton,
    ActionableCard,
    AnimalView,
    OrdererView,
    TooltipWrap,
    ObservationTable,
    AddObservationsButton,
    ReportTable,
    AddReportButton,
    DownloadProbeWorksheetButton,
    EditProbeAnimalButton,
    ElmReportTable,
    AddElmReportButton,
    EditProbeOrdererButton,
    OrdererPracView,
    EditProbeServiceTimeButton,
    ServiceTimeView,
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
      reportEmails: undefined,

      specimens: undefined,
      leadingCodes: undefined,
      organisms: undefined,

      users: undefined,
      interpretationTexts: undefined,
      reportTexts: undefined,
    }
  },
  computed: {
    missingObservations: function () {
      const allAnalysisTypes = [...this.probe.analysisTypes]
      if (this.probe.methodTypes.includes("MLST")) {
        allAnalysisTypes.push("MLST")
      }
      if (this.probe.methodTypes.includes("WGS")) {
        allAnalysisTypes.push("CG_MLST")
      }

      return allAnalysisTypes.filter(at =>
          !this.observations.some(o => this.probe.pathogen == o.pathogen && this.probe.pathogenName == o.pathogenName && o.analysisType === at)
      )
    },
    hasMedicalValidation: function () {
      return checkHasMedicalValidation(this.users)
    },
    isFreshProbe: function () {
      const oneHourAgo = Date.now() - (60 * 60 * 1000)
      return this.probe.createdAt === this.probe.lastChangedAt &&
          new Date(this.probe.createdAt) > oneHourAgo &&
          this.observations.length === 0 &&
          this.reports.length === 0
    },
    missingElmReport: function () {
      if (this.elmReports.length > 0) {
        return false;
      }

      if (!this.probe.patient) {
        return false;
      }

      const toBeReportedPathogens = ['SALMONELLA', 'SHIGELLA', 'LISTERIA_MONOCYTOGENES', 'VIBRIO_CHOLERAE']
      const hasIdentifiedObservation = this.observations.find(o => o.analysisType === 'IDENTIFICATION' && o.organism && toBeReportedPathogens.includes(o.pathogen))
      if (hasIdentifiedObservation) {
        return true;
      }

      const hasStec = this.observations.find(o => o.analysisType === 'EC_STEC' && o.interpretation === 'POS')
      if (hasStec) {
        return true;
      }

      return false;
    }
  },
  methods: {
    addedReport: function (report) {
      this.reports.push(report)
      this.$nextTick(() => {
        document.getElementById('download-report-' + report['@id'])?.click()
      })
    },
    removedProbe: function () {
      window.location = router.probeNew()
    }
  },
  mounted() {
    const {
      probe,
      observations,
      elmReports,
      reports,
      reportEmails,
      specimens,
      leadingCodes,
      organisms,
      users,
      interpretationTexts,
      reportTexts,
    } = preloadApi.getViewActiveProbe()
    this.probe = probe

    this.observations = observations
    this.elmReports = elmReports
    this.reports = reports
    this.reportEmails = reportEmails

    this.specimens = specimens
    this.leadingCodes = leadingCodes
    this.organisms = organisms

    this.users = users
    this.interpretationTexts = interpretationTexts
    this.reportTexts = reportTexts

    const createdAtMs = new Date(this.probe.createdAt).getTime()
    const twoHoursAgoMs = Date.now() - 2 * 60 * 60 * 1000
    if (createdAtMs > twoHoursAgoMs) {
      this.$nextTick(() => {
        this.$refs.downloadWorksheetButton.$el?.focus()
      })
    } else if (this.missingObservations.length > 0) {
      this.$nextTick(() => {
        this.$refs.addObservationsButton.$el?.focus()
      })
    }
  }
}
</script>
