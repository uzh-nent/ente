<template>
  <div>
    <form-field for-id="laboratoryFunction" :label="$t('probe.laboratory_function')" :field="fields.ordererIdentifier">
      <radio id="laboratoryFunction" :choices="laboratoryFunctions" :field="fields.laboratoryFunction"
             v-model="entity.laboratoryFunction" @update:model-value="validateField('laboratoryFunction')"/>
    </form-field>
    <template v-if="entity.laboratoryFunction === 'PRIMARY'">
      <form-field for-id="analysisTypes" :label="$t('service.ecoli_identification')" :field="fields.analysisTypes">
        <checkboxes id="analysisTypes" :choices="primaryAnalysisTypes" :field="fields.analysisTypes"
                    v-model="entity.analysisTypes" @update:model-value="validateField('analysisTypes')"/>
      </form-field>
    </template>
    <template v-if="entity.laboratoryFunction === 'REFERENCE'">
      <form-field for-id="pathogen" :label="$t('service.identification_typing')" :field="fields.pathogen"
                  :fake-required="true">
        <radio id="pathogen" :choices="referencePathogens"
               :field="fields.pathogen"
               v-model="entity.pathogen" @update:model-value="validateField('pathogen')"/>
      </form-field>
      <template v-if="entity.pathogen === null">
        <form-field for-id="pathogenText" :label="$t('probe.pathogen_text')"
                    :field="fields.pathogenText">
          <text-input id="pathogenText" type="text" :field="fields.pathogenText"
                      v-model="entity.pathogenText"
                      @blur="blurField('pathogenText')" @update:modelValue="validateField('pathogenText')"/>
        </form-field>
      </template>
    </template>

    <div class="row">
      <div class="col-md-6">
        <form-field for-id="ordererIdentifier" :label="$t('probe.orderer_identifier')"
                    :field="fields.ordererIdentifier">
          <text-input id="ordererIdentifier" type="text" :field="fields.ordererIdentifier"
                      v-model="entity.ordererIdentifier"
                      @blur="blurField('ordererIdentifier')" @update:modelValue="validateField('ordererIdentifier')"/>
        </form-field>
      </div>
    </div>

    <div>
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
          <radio id="orderer" :choices="orderers" :field="fields.orderer"
                 v-model="entity.orderer" @update:model-value="validateField('orderer')"/>
          <span class="form-text">{{ itemHits }}</span>
        </div>

        <add-organization-button @added="addedOrganization"
                                 :template="{postalCode: searchPostalCode, name: searchName}"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from './utils/form'
import FormField from '../Library/FormLayout/FormField'
import TextInput from '../Library/FormInput/TextInput.vue'
import TextArea from '../Library/FormInput/TextArea.vue'
import DateTimeInput from '../Library/FormInput/DateTimeInput.vue'
import Radio from "../Library/FormInput/Radio.vue";
import Checkboxes from "../Library/FormInput/Checkboxes.vue";
import {paginatedQuery} from "../../mixins/table";
import {api} from "../../services/api";
import {createQuery} from "../../services/query";
import {formatOrganizationShort} from "../../services/formatter";
import AddOrganizationButton from "../Action/AddOrganizationButton.vue";
import OrganizationView from "../View/OrganizationView.vue";

const createLaboratoryFunctions = function (translator) {
  const values = ['REFERENCE', 'PRIMARY']
  return values.map(value => ({label: translator(`probe._laboratory_function.${value}`), value}))
}

const createReferencePathogens = function (translator) {
  const values = ['SALMONELLA', 'SHIGELLA', 'YERSINIA', 'LISTERIA_MONOCYTOGENES', 'VIBRIO_CHOLERAE', 'ESCHERICHIA_COLI', 'CAMPYLOBACTER']
  return values
      .map(value => ({label: translator(`probe._pathogen.${value}`), value}))
      .concat({label: translator('probe._pathogen.OTHER'), value: null})
}

const createPrimaryAnalysisTypes = function (translator) {
  const values = ['EC_STEC', 'EC_EPEC', 'EC_ETEC', 'EC_EIEC', 'EC_EAEC']
  return values.map(value => ({label: translator(`probe._analysis_type.${value}`), value}))
}
export default {
  emits: ['update'],
  components: {
    OrganizationView,
    AddOrganizationButton,
    Checkboxes,
    Radio,
    DateTimeInput,
    TextArea,
    TextInput,
    FormField
  },
  mixins: [
    templatedForm,
    paginatedQuery(10, api.getPaginatedOrganisations),
  ],
  data() {
    return {
      fields: {
        laboratoryFunction: createField(requiredRule),
        pathogen: createField(),
        pathogenText: createField(),
        analysisTypes: createField(requiredRule),

        ordererIdentifier: createField(requiredRule),
        orderer: createField(requiredRule),
      },
      entity: {
        laboratoryFunction: null,
        pathogen: null,
        pathogenText: null,
        analysisTypes: null,

        ordererIdentifier: null,
        orderer: null,
      },

      searchName: "",
      searchPostalCode: "",
    }
  },
  computed: {
    laboratoryFunctions: function () {
      return createLaboratoryFunctions(this.$t)
    },
    referencePathogens: function () {
      return createReferencePathogens(this.$t)
    },
    primaryAnalysisTypes: function () {
      return createPrimaryAnalysisTypes(this.$t)
    },
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
  },
  watch: {
    'entity.laboratoryFunction': {
      immediate: true,
      handler: function (laboratoryFunction) {
        if (laboratoryFunction === 'REFERENCE') {
          this.entity.pathogen = 'SALMONELLA'
          this.entity.pathogenText = null
          this.entity.analysisTypes = ['IDENTIFICATION']
        }

        if (laboratoryFunction === 'PRIMARY') {
          this.entity.pathogen = 'ESCHERICHIA_COLI'
          this.entity.pathogenText = null
          this.entity.analysisTypes = []
        }
      },
    }
  }
}
</script>
