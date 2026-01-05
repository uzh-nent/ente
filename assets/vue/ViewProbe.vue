<template>
  <div class="row" v-if="probe">
    <div class="col-lg-4">
      <h3>{{ $t('probe.service_request') }}</h3>
      <actionable-view>
        <service-request-view :probe="probe"/>
        <template v-slot:actions v-if="observations.length === 0 && !probe.finishedAt">
          <edit-probe-service-request-button :probe="probe"/>
        </template>
      </actionable-view>

      <h3 class="mt-5">{{ $t('probe.orderer') }}</h3>
      <template v-if="probe.ordererOrg">
        <actionable-view>
          <orderer-org-view :probe="probe"/>
          <template v-slot:actions v-if="!probe.finishedAt">
            <edit-probe-orderer-org-button :probe="probe"/>
          </template>
        </actionable-view>
      </template>
      <template v-if="probe.ordererPrac">
        <actionable-view>
          <orderer-prac-view :probe="probe"/>
          <template v-slot:actions v-if="!probe.finishedAt">
            <edit-probe-orderer-prac-button :probe="probe"/>
          </template>
        </actionable-view>
      </template>

      <h3 class="mt-5">{{ $t('probe._name') }}</h3>
      <actionable-view>
        <specimen-meta-view :probe="probe" :specimens="specimens"/>
        <template v-slot:actions v-if="!probe.finishedAt">
          <edit-probe-specimen-meta-button :specimens="specimens" :probe="probe"/>
        </template>
      </actionable-view>

      <template v-if="probe.specimenSource === 'HUMAN'">
        <h3 class="mt-5">{{ $t('patient._name') }}</h3>
        <actionable-view>
          <patient-view :probe="probe"/>
          <template v-slot:actions v-if="!probe.finishedAt">
            <edit-probe-patient-button :probe="probe"/>
          </template>
        </actionable-view>
      </template>

      <template v-if="probe.specimenSource === 'ANIMAL'">
        <h3 class="mt-5">{{ $t('animal_keeper._name') }}</h3>
        <actionable-view>
          <owner-view :probe="probe"/>
          <template v-slot:actions v-if="!probe.finishedAt">
            <edit-probe-owner-button :probe="probe"/>
          </template>
        </actionable-view>
      </template>
    </div>
    <div class="col-lg-8">
      <h3>{{ $t('probe.progress') }}</h3>
      <actionable-view class="w-50">
        <service-time-view :probe="probe" :users="users"/>
        <template v-slot:actions v-if="!probe.finishedAt">
          <edit-probe-service-time-button :probe="probe"/>
        </template>
      </actionable-view>

      <download-probe-worksheet-button class="mt-5" :probe="probe" :has-observations="observations.length > 0"/>

      <div class="mt-5">
        <h3>{{ $t('observation._name') }}</h3>
        <add-identification-observation-button
            v-if="missingIdentificationObservation"
            class="mt-2" ref="addIdentificationObservationButton"
            @added="observations.push($event)"
            :probe="probe" :organisms="organisms"/>
        <div v-if="identificationObservation" class="w-50 mt-2">
          <actionable-view>
            <identification-view
                :organisms="organisms" :observation="identificationObservation"/>
            <template v-slot:actions v-if="!probe.finishedAt">
              <edit-identification-observation-button :probe="probe" :organisms="organisms"
                                                      :observation="identificationObservation"/>
            </template>
            <template v-slot:footer>
              <attribution-view :users="users" :entity="identificationObservation"/>
            </template>
          </actionable-view>
        </div>
        <add-test-observations-button
            v-if="missingTestObservations.length > 0"
            class="mt-2" ref="addTestObservationsButton"
            @added="observations.push($event)"
            :probe="probe" :missing-analysis-types="missingTestObservations"/>
        <test-observation-table
            v-if="testObservations.length > 0" class="mt-2"
            :users="users" :observations="observations"/>

        <div class="mt-5" v-if="probe.patient && observations.length > 0 && (!probe.finishedAt || elmReports.length > 0)">
          <h3>{{ $t('elm_report._name') }}</h3>
          <add-elm-report-button
              v-if="!probe.finishedAt"
              :probe="probe" :observations="observations"
              :leading-codes="leadingCodes" :organisms="organisms" :specimens="specimens"
              @added="elmReports.push($event)"
          />
          <elm-report-table
              class="mt-2"
              v-if="elmReports.length > 0" :reports="elmReports"
              :users="users" :organisms="organisms" :leading-codes="leadingCodes"/>
        </div>
        <div class="mt-5" v-if="!missingIdentificationObservation && missingTestObservations.length === 0">
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
import OwnerView from "./components/View/Probe/OwnerView.vue";
import ActionableView from "./components/Library/View/ActionableView.vue";
import EditProbeServiceRequestButton from "./components/Action/EditProbeServiceRequestButton.vue";
import EditProbePatientButton from "./components/Action/EditProbePatientButton.vue";
import EditProbeOwnerButton from "./components/Action/EditProbeOwnerButton.vue";
import SpecimenMetaView from "./components/View/Probe/SpecimenMetaView.vue";
import EditProbeSpecimenMetaButton from "./components/Action/EditProbeSpecimenMetaButton.vue";
import ServiceTimeForm from "./components/Form/Probe/ServiceTimeForm.vue";
import ServiceTimeView from "./components/View/Probe/ServiceTimeView.vue";
import EditProbeServiceTimeButton from "./components/Action/EditProbeServiceTimeButton.vue";
import AddIdentificationObservationButton from "./components/Action/AddIdentificationObservationButton.vue";
import IdentificationView from "./components/View/Observation/IdentificationView.vue";
import EditIdentificationObservationButton from "./components/Action/EditIdentificationObservationButton.vue";
import OrdererOrgView from "./components/View/Probe/OrdererOrgView.vue";
import EditProbeOrdererOrgButton from "./components/Action/EditProbeOrdererOrgButton.vue";
import OrdererPracView from "./components/View/Probe/OrdererPracView.vue";
import EditProbeOrdererPracButton from "./components/Action/EditProbeOrdererPracButton.vue";
import AddElmReportButton from "./components/Action/AddElmReportButton.vue";
import AttributionView from "./components/View/AttributionView.vue";
import ElmReportTable from "./components/View/ElmReportTable.vue";
import ToggleFinishedButton from "./components/Action/ToggleFinishedButton.vue";
import AddTestObservationsButton from "./components/Action/AddTestObservationsButton.vue";
import TestObservationTable from "./components/View/TestObservationTable.vue";
import DownloadProbeWorksheetButton from "./components/Action/DownloadProbeWorksheetButton.vue";

export default {
  emits: ['added'],
  components: {
    DownloadProbeWorksheetButton,
    TestObservationTable,
    AddTestObservationsButton,
    ToggleFinishedButton,
    ElmReportTable,
    AttributionView,
    AddElmReportButton,
    EditProbeOrdererPracButton,
    OrdererPracView,
    EditProbeOrdererOrgButton,
    OrdererOrgView,
    EditIdentificationObservationButton,
    IdentificationView,
    AddIdentificationObservationButton,
    EditProbeServiceTimeButton,
    ServiceTimeView,
    ServiceTimeForm,
    EditProbeSpecimenMetaButton,
    SpecimenMetaView,
    EditProbeOwnerButton,
    EditProbePatientButton,
    EditProbeServiceRequestButton,
    ActionableView,
    OwnerView,
    PatientView,
    ServiceRequestView,
  },
  data() {
    return {
      probe: undefined,

      users: undefined,
      specimens: undefined,
      leadingCodes: undefined,
      organisms: undefined,

      observations: undefined,
      elmReports: undefined,
      reports: undefined
    }
  },
  computed: {
    missingIdentificationObservation: function () {
      return this.probe.analysisTypes.some(at => at === 'IDENTIFICATION') &&
          !this.observations.some(o => o.analysisType === 'IDENTIFICATION')
    },
    identificationObservation: function () {
      return this.observations.find(o => o.analysisType === 'IDENTIFICATION')
    },
    missingTestObservations: function () {
      return this.probe.analysisTypes.filter(at => at !== 'IDENTIFICATION' &&
          !this.observations.some(o => o.analysisType === at))
    },
    testObservations: function () {
      return this.observations.filter(o => o.analysisType !== 'IDENTIFICATION')
    },
  },
  mounted() {
    const {
      probe,
      users,
      specimens,
      leadingCodes,
      organisms,
      observations,
      elmReports,
      reports
    } = preloadApi.getViewActiveProbe()
    this.probe = probe

    this.users = users
    this.specimens = specimens
    this.leadingCodes = leadingCodes
    this.organisms = organisms

    this.observations = observations
    this.elmReports = elmReports
    this.reports = reports

    if (this.missingIdentificationObservation) {
      this.$nextTick(() => {
        this.$refs.addIdentificationObservationButton.$el?.focus()
      })
    } else if (this.missingTestObservations.length > 0) {
      this.$nextTick(() => {
        this.$refs.addTestObservationsButton.$el?.focus()
      })
    }
  }
}
</script>
