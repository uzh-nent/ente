<template>
  <div>
    <div class="row">
      <div class="col-md-6">
        <form-field for-id="requisitionIdentifier" :label="$t('probe.requisition_identifier')"
                    :field="fields.requisitionIdentifier">
          <text-input id="requisitionIdentifier" type="text" :field="fields.requisitionIdentifier"
                      v-model="entity.requisitionIdentifier"
                      @blur="blurField('requisitionIdentifier')" @update:modelValue="validateField('requisitionIdentifier')"/>
        </form-field>
      </div>
    </div>
    <form-field for-id="ordererPrac" :label="$t('practitioner._name')" :field="fields.ordererPrac">
      <actionable-preview class="mb-2" v-if="entity.ordererPrac && entity.ordererPrac['@id']">
        <practitioner-view :practitioner="entity.ordererPrac"/>
        <template #actions>
          <edit-practitioner-button :practitioner="entity.ordererPrac"/>
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
        <radio id="ordererPrac" :choices="ordererPracs" :field="fields.ordererPrac" :value-string="value => value['@id']"
               v-model="entity.ordererPrac" @update:model-value="validateField('ordererPrac')"/>
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
import {formatPractitionerShort} from "../../../services/domain/formatter";
import AddPractitionerButton from "../../Action/AddPractitionerButton.vue";
import PractitionerView from "../../View/PractitionerView.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";
import EditPatientButton from "../../Action/EditPatientButton.vue";
import ActionablePreview from "../../Library/View/ActionablePreview.vue";
import PatientView from "../../View/PatientView.vue";
import EditPractitionerButton from "../../Action/EditPractitionerButton.vue";

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
    templatedForm,
    paginatedQuery(10, api.getPaginatedPractitioners),
  ],
  data() {
    return {
      fields: {
        requisitionIdentifier: createField(requiredRule),
        ordererPrac: createField(requiredRule),
      },
      entity: {
        requisitionIdentifier: null,
        ordererPrac: null,
      },

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
    ordererPracs: function () {
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
    items: {
      handler: function (items) {
        if (items.length === 1) {
          this.entity.ordererPrac = items[0]
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
