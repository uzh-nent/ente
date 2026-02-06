<template>
    <form-field for-id="ordererOrg" :label="$t('patient._name')">
      <actionable-preview class="mb-2" v-if="patient">
        <patient-view :patient="patient"/>
        <template #actions>
          <edit-linked-patient-button :entity="patient" @update="patientOverride = $event" />
        </template>
      </actionable-preview>

      <div class="d-flex flex-row reset-table-styles gap-2 mb-2">
        <date-time-input
            class="mw-10" id="birthDateFilter" format="date"
            :placeholder="$t('_view.filter_by_birth_date')"
            v-model="filterBirthDate"/>
        <input type="text" class="form-control flex-grow-1"
               :placeholder="$t('_view.search_by_ahv_numer')"
               v-model="searchAhvNumber">
        <add-patient-button
            button-size="sm"
            :template="{birthDate: filterBirthDate, ahvNumber: searchAhvNumber}" @added="addedPatient"/>
      </div>

      <div class="mb-2">
        <radio id="ordererOrg" :choices="patients" :value-string="value => value['@id']"
               v-model="selectedPatient" />
        <span class="form-text">{{ itemHits }}</span>
      </div>

    </form-field>
</template>

<script>
import FormField from '../Library/FormLayout/FormField.vue'
import Radio from "../Library/FormInput/Radio.vue";
import {paginatedQuery} from "../View/utils/table";
import {api} from "../../services/api";
import {orderFilter, sanitizeSearchFilter} from "../../services/query";
import {formatPatientShort} from "../../services/domain/formatter";
import AddPatientButton from "./AddPatientButton.vue";
import TextInput from "../Library/FormInput/TextInput.vue";
import ActionablePreview from "../Library/View/ActionablePreview.vue";
import PatientView from "../View/PatientView.vue";
import EditPatientButton from "./EditPatientButton.vue";
import {probeConverter} from "../../services/domain/converters";
import EditLinkedPatientButton from "./EditLinkedPatientButton.vue";
import DateTimeInput from "../Library/FormInput/DateTimeInput.vue";

export default {
  emits: ['update'],
  components: {
    DateTimeInput,
    EditLinkedPatientButton,
    ActionablePreview, EditPatientButton,
    TextInput,
    Radio,
    PatientView,
    AddPatientButton,
    FormField
  },
  mixins: [
    paginatedQuery(10, api.getPaginatedPatients),
  ],
  data() {
    return {
      selectedPatient: null,
      patientOverride: null,

      searchAhvNumber: "",
      filterBirthDate: "",
    }
  },
  props: {
    probe: {
      type: Object,
      default: null
    }
  },
  computed: {
    patient: function () {
      if (this.patientOverride) {
        return this.patientOverride
      }

      if (this.selectedPatient) {
        return this.selectedPatient
      }

      if (this.probe) {
        return probeConverter.reconstructPatient(this.probe)
      }

      return null;
    },
    query: function () {
      if (this.filterBirthDate?.length !== 10) {
        return null
      }

      const filter = sanitizeSearchFilter({birthDate: this.filterBirthDate, ahvNumber: this.searchAhvNumber})
      const order = orderFilter([{property: 'birthDate', order: 'asc'}, {property: 'ahvNumber', order: 'asc'}])
      return {...filter, ...order}
    },
    patients: function () {
      return this.items.map(item => ({label: formatPatientShort(item), value: item}))
    },
    itemHits: function () {
      if (!this.query) {
        return "";
      }

      let hits = this.items.length;
      if (this.items.length < this.totalItems) {
        hits = `${hits}+`
      }

      return this.$t('_action.hits', {hits: hits})
    }
  },
  watch: {
    items: {
      handler: function (items) {
        if (items.length === 1 && !this.probe?.ordererOrg) {
          this.selectedPatient = items[0]
        }
      }
    },
    selectedPatient: {
      handler: function () {
        this.patientOverride = null
      }
    },
    patient: {
      handler: function (patient) {
        const orderOrg = probeConverter.copyFromPatient(patient)
        const patch = {}
        for (const key in orderOrg) {
          if (orderOrg.hasOwnProperty(key) && (!this.probe || orderOrg[key] !== this.probe[key])) {
            patch[key] = orderOrg[key]
          }
        }

        this.$emit("update", patch)
      }
    }
  },
  methods: {
    addedPatient: function (patient) {
      this.filterBirthDate = this.searchAhvNumber = null // first empty to ensure afterwards reload
      this.filterBirthDate = patient.birthDate
      this.searchAhvNumber = patient.ahvNumber
    },
  }
}
</script>

<style scoped>
.mw-10 {
  max-width: 10em;
}
</style>
