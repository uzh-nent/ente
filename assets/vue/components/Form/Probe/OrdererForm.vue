<template>
  <div>
    <div class="row">
      <div class="col-md-6">
        <form-field for-id="ordererIdentifier" :label="$t('probe.orderer_identifier')"
                    :field="fields.ordererIdentifier">
          <text-input id="ordererIdentifier" type="text" :field="fields.ordererIdentifier" autofocus
                      v-model="entity.ordererIdentifier"
                      @blur="blurField('ordererIdentifier')" @update:modelValue="validateField('ordererIdentifier')"/>
        </form-field>
      </div>
    </div>
    <form-field for-id="orderer" :label="$t('probe.orderer')" :field="fields.orderer">
      <organization-view class="mb-2" v-if="entity.orderer" :organization="entity.orderer"/>

      <div class="d-flex flex-row reset-table-styles gap-2 mb-2">
        <input type="text" class="form-control mw-5"
               :placeholder="$t('address.postal_code')"
               v-model="searchPostalCode">
        <input type="text" class="form-control mw-30"
               :placeholder="$t('_view.search_by_name')"
               v-model="searchName">
      </div>

      <div class="mb-2">
        <radio id="orderer" :choices="orderers" :field="fields.orderer" :value-string="value => value['@id']"
               v-model="entity.orderer" @update:model-value="validateField('orderer')"/>
        <span class="form-text">{{ itemHits }}</span>
      </div>

      <add-organization-button @added="addedOrganization"
                               :template="{postalCode: searchPostalCode, name: searchName}"/>
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField.vue'
import Radio from "../../Library/FormInput/Radio.vue";
import {paginatedQuery} from "../../../mixins/table";
import {api} from "../../../services/api";
import {createQuery} from "../../../services/query";
import {formatOrganizationShort} from "../../../services/formatter";
import AddOrganizationButton from "../../Action/AddOrganizationButton.vue";
import OrganizationView from "../../View/OrganizationView.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";

export default {
  emits: ['update'],
  components: {
    TextInput,
    Radio,
    OrganizationView,
    AddOrganizationButton,
    FormField
  },
  mixins: [
    templatedForm,
    paginatedQuery(10, api.getPaginatedOrganisations),
  ],
  data() {
    return {
      fields: {
        ordererIdentifier: createField(requiredRule),
        orderer: createField(requiredRule),
      },
      entity: {
        ordererIdentifier: null,
        orderer: null,
      },

      searchName: "",
      searchPostalCode: "",
    }
  },
  computed: {
    query: function () {
      const filter = {name: this.searchName, postalCode: this.searchPostalCode}
      const order = [{property: 'postalCode', order: 'asc'}, {property: 'name', order: 'asc'}]
      return createQuery({}, [], ['name', 'postalCode'], [], filter, order)
    },
    orderers: function () {
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
  methods: {
    addedOrganization: function (organization) {
      this.searchPostalCode = organization.postalCode
      this.searchName = organization.name
      this.entity.orderer = organization
    }
  }
}
</script>
