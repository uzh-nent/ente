<template>
    <form-field for-id="ordererOrg" :label="$t('practitioner._name')">
      <actionable-preview class="mb-2" v-if="practitioner">
        <practitioner-view :practitioner="practitioner"/>
        <template #actions>
          <edit-linked-practitioner-button class="m-2" :entity="practitioner" @update="practitionerOverride = $event" />
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
        <radio id="ordererOrg" :choices="practitioners" :value-string="value => value['@id']"
               v-model="selectedPractitioner" />
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
import {formatPractitionerShort} from "../../services/domain/formatter";
import AddPractitionerButton from "./AddPractitionerButton.vue";
import PractitionerView from "../View/PractitionerView.vue";
import TextInput from "../Library/FormInput/TextInput.vue";
import EditPatientButton from "./EditPatientButton.vue";
import ActionablePreview from "../Library/View/ActionablePreview.vue";
import PatientView from "../View/PatientView.vue";
import EditPractitionerButton from "./EditPractitionerButton.vue";
import {probeConverter} from "../../services/domain/converters";
import EditLinkedPractitionerButton from "./EditLinkedPractitionerButton.vue";
import EditLinkedAnimalKeeperButton from "./EditLinkedAnimalKeeperButton.vue";

export default {
  emits: ['update'],
  components: {
    EditLinkedAnimalKeeperButton,
    EditLinkedPractitionerButton,
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
      selectedPractitioner: null,
      practitionerOverride: null,

      searchFamilyName: "",
      searchPostalCode: "",
    }
  },
  props: {
    probe: {
      type: Object,
      default: null
    }
  },
  computed: {
    practitioner: function () {
      if (this.practitionerOverride) {
        return this.practitionerOverride
      }

      if (this.selectedPractitioner) {
        return this.selectedPractitioner
      }

      if (this.probe) {
        return probeConverter.reconstructOrdererPrac(this.probe)
      }

      return null;
    },
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
          this.selectedPractitioner = items[0]
        }
      }
    },
    selectedPractitioner: {
      handler: function () {
        this.practitionerOverride = null
      }
    },
    practitioner: {
      handler: function (practitioner) {
        const orderOrg = probeConverter.copyFromPractitioner(practitioner)
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
    addedPractitioner: function (practitioner) {
      this.searchPostalCode = this.searchFamilyName = null // first empty to ensure afterwards reload
      this.searchPostalCode = practitioner.postalCode
      this.searchFamilyName = practitioner.familyName
    },
  }
}
</script>
