<template>
  <div>
    <form-field for-id="ordererOrg" :label="$t('practitioner._name')" :field="practitionerField">
      <actionable-preview class="mb-2" v-if="practitioner && practitioner['@id']">
        <practitioner-view :practitioner="practitioner"/>
        <template #actions>
          <edit-practitioner-button :practitioner="practitioner"/>
        </template>
      </actionable-preview>

      <div class="d-flex flex-row reset-table-styles gap-2 mb-2">
        <input type="text" class="form-control mw-5"
               :placeholder="$t('address.postal_code')"
               v-model="searchPostalCode">
        <input type="text" class="form-control flex-grow-1"
               :placeholder="$t('_view.search_by_family_name')"
               v-model="searchFamilyName">
        <add-practitioner-button
            button-size="sm"
            :template="{postalCode: searchPostalCode, familyName: searchFamilyName}" @added="addedPractitioner"/>
      </div>

      <div class="mb-2">
        <radio id="ordererOrg" :choices="practitioners" :field="practitionerField" :value-string="value => value['@id']"
               v-model="practitioner" />
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
import {orderFilter, sanitizeSearchFilter} from "../../../services/query";
import {formatPractitionerAddress, formatPractitionerShort} from "../../../services/domain/formatter";
import AddPractitionerButton from "../../Action/AddPractitionerButton.vue";
import PractitionerView from "../../View/PractitionerView.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";
import EditPatientButton from "../../Action/EditPatientButton.vue";
import ActionablePreview from "../../Library/View/ActionablePreview.vue";
import PatientView from "../../View/PatientView.vue";
import EditPractitionerButton from "../../Action/EditPractitionerButton.vue";
import {addressConverter} from "../../../services/domain/converters";

export default {
  emits: ['update'],
  components: {
    EditPractitionerButton,
    PatientView, ActionablePreview, EditPatientButton,
    TextInput,
    Radio,
    PractitionerView,
    AddPractitionerButton,
    FormField
  },
  mixins: [
    paginatedQuery(10, api.getPaginatedPractitioners),
  ],
  data() {
    return {
      practitioner: null,
      practitionerField: createField(requiredRule),

      searchFamilyName: "",
      searchPostalCode: "",
    }
  },
  computed: {
    query: function () {
      if (!this.searchPostalCode && !this.searchFamilyName) {
        return null
      }

      const filter = sanitizeSearchFilter({postalCode: this.searchPostalCode, familyName: this.searchFamilyName})
      const order = orderFilter([{property: 'postalCode', order: 'asc'}, {property: 'familyName', order: 'asc'}])
      return {...filter, ...order}
    },
    practitioners: function () {
      return this.items.map(item => ({label: formatPractitionerShort(item), value: item}))
    },
    itemHits: function () {
      let hits = this.items.length;
      if (this.items.length < this.totalItems) {
        hits = `${hits}+`
      }

      return this.$t('_action.hits', {hits: hits})
    }
  },
  watch: {
    practitioner: {
      handler: function (practitioner) {
        if (practitioner) {
          this.$emit('update', addressConverter.createFromPractitioner(practitioner))
        } else {
          this.$emit('update', null)
        }
      }
    },
    items: {
      handler: function (items) {
        if (items.length === 1) {
          this.practitioner = items[0]
        }
      }
    }
  },
  methods: {
    addedPractitioner: function (practitioner) {
      this.searchPostalCode = this.searchFamilyName = null // first empty to ensure afterwards reload
      this.searchPostalCode = practitioner.postalCode
      this.searchFamilyName = practitioner.familyName
    },
  }
}
</script>
