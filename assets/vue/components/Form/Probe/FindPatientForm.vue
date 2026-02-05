<template>
  <div>
    <form-field for-id="patient" :label="$t('patient._name')" :field="fields.patient">
      <actionable-preview class="mb-2" v-if="entity.patient && entity.patient['@id']">
        <patient-view :patient="entity.patient"/>
        <template #actions>
          <edit-patient-button :patient="entity.patient"/>
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
            @added="addedPatient" :template="{birthDate: filterBirthDate, ahvNumber: searchAhvNumber}"/>
      </div>

      <div class="mb-2">
        <radio id="patient" :choices="patients" :field="fields.patient"
               :value-string="value => value['@id']"
               v-model="entity.patient" @update:model-value="validateField('patient')"/>
        <span class="form-text">{{ itemHits }}</span>
      </div>
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField.vue'
import Radio from "../../Library/FormInput/Radio.vue";
import {paginatedQuery} from "../../View/utils/table";
import {api} from "../../../services/api";
import { orderFilter, sanitizeSearchFilter} from "../../../services/query";
import {formatPatientShort} from "../../../services/domain/formatter";
import AddPatientButton from "../../Action/AddPatientButton.vue";
import PatientView from "../../View/PatientView.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";
import DateTimeInput from "../../Library/FormInput/DateTimeInput.vue";
import ActionablePreview from "../../Library/View/ActionablePreview.vue";
import EditPatientButton from "../../Action/EditPatientButton.vue";

export default {
  emits: ['update'],
  components: {
    EditPatientButton,
    ActionablePreview,
    DateTimeInput,
    TextInput,
    Radio,
    PatientView,
    AddPatientButton,
    FormField
  },
  mixins: [
    templatedForm,
    paginatedQuery(10, api.getPaginatedPatients),
  ],
  data() {
    return {
      fields: {
        patient: createField(requiredRule),
      },
      entity: {
        patient: null,
      },

      filterBirthDate: null,
      searchAhvNumber: "",
    }
  },
  watch: {
    items: {
      handler: function (items) {
        if (items.length === 1) {
          this.entity.patient = items[0]
        }
      }
    }
  },
  computed: {
    query: function () {
      if (this.filterBirthDate?.length !== 10) {
        return null
      }

      const filter = sanitizeSearchFilter({birthDate: this.filterBirthDate, ahvNumber: this.searchAhvNumber})
      const order = orderFilter([{property: 'familyName', order: 'asc'}, {property: 'postalCode', order: 'asc'}])
      return {...filter, ...order}
    },
    patients: function () {
      return this.items.map(item => ({label: formatPatientShort(item), value: item}))
    },
    itemHits: function () {
      let hits = this.items.length;
      if (this.items.length < this.totalItems) {
        hits = `${hits}+`
      }

      return this.$t('_action.hits', {hits: hits})
    }
  },
  methods: {
    addedPatient: function (patient) {
      this.filterBirthDate = this.searchAhvNumber = null // first empty to ensure afterwards reload
      this.filterBirthDate = patient.birthDate
      this.searchAhvNumber = patient.ahvNumber
    }
  }
}
</script>
