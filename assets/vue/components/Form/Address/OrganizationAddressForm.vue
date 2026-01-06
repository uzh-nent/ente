<template>
  <div>
    <form-field for-id="ordererOrg" :label="$t('organization._name')" :field="organizationField">
      <actionable-preview class="mb-2" v-if="organization && organization['@id']">
        <organization-view :organization="organization"/>
        <template #actions>
          <edit-organization-button :organization="organization"/>
        </template>
      </actionable-preview>

      <div class="d-flex flex-row reset-table-styles gap-2 mb-2">
        <input type="text" class="form-control mw-5"
               :placeholder="$t('address.postal_code')"
               v-model="searchPostalCode">
        <input type="text" class="form-control flex-grow-1"
               :placeholder="$t('_view.search_by_name')"
               v-model="searchName">
        <add-organization-button
            button-size="sm"
            :template="{postalCode: searchPostalCode, name: searchName}" @added="addedOrganization"/>
      </div>

      <div class="mb-2">
        <radio id="ordererOrg" :choices="organizations" :field="organizationField" :value-string="value => value['@id']"
               v-model="organization" />
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
import {createQuery} from "../../../services/query";
import {formatOrganizationAddress, formatOrganizationShort} from "../../../services/domain/formatter";
import AddOrganizationButton from "../../Action/AddOrganizationButton.vue";
import OrganizationView from "../../View/OrganizationView.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";
import EditPatientButton from "../../Action/EditPatientButton.vue";
import ActionablePreview from "../../Library/View/ActionablePreview.vue";
import PatientView from "../../View/PatientView.vue";
import EditOrganizationButton from "../../Action/EditOrganizationButton.vue";

export default {
  emits: ['update'],
  components: {
    EditOrganizationButton,
    PatientView, ActionablePreview, EditPatientButton,
    TextInput,
    Radio,
    OrganizationView,
    AddOrganizationButton,
    FormField
  },
  mixins: [
    paginatedQuery(10, api.getPaginatedOrganisations),
  ],
  data() {
    return {
      organization: null,
      organizationField: createField(requiredRule),

      searchName: "",
      searchPostalCode: "",
    }
  },
  computed: {
    query: function () {
      if (!this.searchPostalCode && !this.searchName) {
        return null
      }

      const filter = {postalCode: this.searchPostalCode, name: this.searchName}
      const order = [{property: 'postalCode', order: 'asc'}, {property: 'name', order: 'asc'}]
      return createQuery({}, [], ['name', 'postalCode'], [], filter, order)
    },
    organizations: function () {
      return this.items.map(item => ({label: formatOrganizationShort(item), value: item}))
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
    organization: {
      handler: function (organization) {
        if (organization) {
          this.$emit('update', formatOrganizationAddress(organization))
        } else {
          this.$emit('update', null)
        }
      }
    },
    items: {
      handler: function (items) {
        if (items.length === 1) {
          this.organization = items[0]
        }
      }
    }
  },
  methods: {
    addedOrganization: function (organization) {
      this.searchPostalCode = this.searchName = null // first empty to ensure afterwards reload
      this.searchPostalCode = organization.postalCode
      this.searchName = organization.name
    },
  }
}
</script>
