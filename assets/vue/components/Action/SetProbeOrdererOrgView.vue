<template>
    <form-field for-id="ordererOrg" :label="$t('organization._name')">
      <actionable-preview class="mb-2" v-if="organization">
        <organization-view :organization="organization"/>
        <template #actions>
          <edit-linked-organization-button
              class="m-2" :can-unlink="canUnlink" :entity="organization" @update="organizationOverride = $event" />
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
        <radio id="ordererOrg" :choices="organizations" :value-string="value => value['@id']"
               v-model="selectedOrganization" />
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
import {formatOrganizationShort} from "../../services/domain/formatter";
import AddOrganizationButton from "./AddOrganizationButton.vue";
import OrganizationView from "../View/OrganizationView.vue";
import TextInput from "../Library/FormInput/TextInput.vue";
import EditPatientButton from "./EditPatientButton.vue";
import ActionablePreview from "../Library/View/ActionablePreview.vue";
import PatientView from "../View/PatientView.vue";
import EditOrganizationButton from "./EditOrganizationButton.vue";
import {probeConverter} from "../../services/domain/converters";
import EditLinkedOrganizationButton from "./EditLinkedOrganizationButton.vue";
import {createCleanPatch} from "./utils/linkedEntity";

export default {
  emits: ['update'],
  components: {
    EditLinkedOrganizationButton,
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
      selectedOrganization: null,
      organizationOverride: undefined,

      searchName: "",
      searchPostalCode: "",
    }
  },
  props: {
    probe: {
      type: Object,
      default: null
    },
    canUnlink: {
      type: Boolean,
      default: false
    },
  },
  computed: {
    organization: function () {
      if (this.organizationOverride !== undefined) {
        return this.organizationOverride
      }

      if (this.selectedOrganization) {
        return this.selectedOrganization
      }

      if (this.probe) {
        return probeConverter.reconstructOrdererOrg(this.probe)
      }

      return null;
    },
    query: function () {
      if (!this.searchPostalCode && !this.searchName) {
        return null
      }

      const filter = sanitizeSearchFilter({postalCode: this.searchPostalCode, name: this.searchName})
      const order = orderFilter([{property: 'postalCode', order: 'asc'}, {property: 'name', order: 'asc'}])
      return {...filter, ...order}
    },
    organizations: function () {
      return this.items.map(item => ({label: formatOrganizationShort(item), value: item}))
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
          this.selectedOrganization = items[0]
        }
      }
    },
    selectedOrganization: {
      handler: function () {
        this.organizationOverride = undefined
      }
    },
    organizationOverride: {
      handler: function () {
        if (this.organizationOverride === null) {
          this.selectedOrganization = null
        }
      }
    },
    organization: {
      handler: function (organization) {
        const copy = probeConverter.copyFromOrganization(organization ?? {})
        const patch = createCleanPatch(this.probe, copy)
        this.$emit("update", patch)
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
