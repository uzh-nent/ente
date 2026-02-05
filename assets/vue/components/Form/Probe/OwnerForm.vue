<template>
  <div>
    <div class="row">
      <div class="col-md-6">
        <form-field for-id="animalName" :label="$t('probe.animal_name')"
                    :field="fields.animalName">
          <text-input id="animalName" type="text" :field="fields.animalName"
                      v-model="entity.animalName"
                      @blur="blurField('animalName')" @update:modelValue="validateField('animalName')"/>
        </form-field>
      </div>
    </div>
    <form-field for-id="animalKeeper" :label="$t('animal_keeper._name')" :field="fields.animalKeeper">
      <actionable-preview class="mb-2" v-if="entity.animalKeeper && entity.animalKeeper['@id']">
        <animal-keeper-view :animal-keeper="entity.animalKeeper"/>
        <template #actions>
          <edit-animal-keeper-button :animal-keeper="entity.animalKeeper" />
        </template>
      </actionable-preview>

      <div class="d-flex flex-row reset-table-styles gap-2 mb-2">
        <input type="text" class="form-control mw-5"
               :placeholder="$t('address.postal_code')"
               v-model="searchPostalCode">
        <input type="text" class="form-control flex-grow-1"
               :placeholder="$t('_view.search_by_name')"
               v-model="searchName">
        <add-animal-keeper-button
            button-size="sm"
            @added="addedAnimalKeeper" :template="{postalCode: searchPostalCode, name: searchName}"/>
      </div>

      <div class="mb-2">
        <radio id="animalKeeper" :choices="animalKeepers" :field="fields.animalKeeper"
               :value-string="value => value['@id']"
               v-model="entity.animalKeeper" @update:model-value="validateField('animalKeeper')"/>
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
import {formatAnimalKeeperShort} from "../../../services/domain/formatter";
import AddAnimalKeeperButton from "../../Action/AddAnimalKeeperButton.vue";
import AnimalKeeperView from "../../View/AnimalKeeperView.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";
import EditOrganizationButton from "../../Action/EditOrganizationButton.vue";
import ActionablePreview from "../../Library/View/ActionablePreview.vue";
import OrganizationView from "../../View/OrganizationView.vue";
import EditAnimalKeeperButton from "../../Action/EditAnimalKeeperButton.vue";
import EditPatientButton from "../../Action/EditPatientButton.vue";

export default {
  emits: ['update'],
  components: {
    EditPatientButton,
    EditAnimalKeeperButton,
    OrganizationView, ActionablePreview, EditOrganizationButton,
    TextInput,
    Radio,
    AnimalKeeperView,
    AddAnimalKeeperButton,
    FormField
  },
  mixins: [
    templatedForm,
    paginatedQuery(10, api.getPaginatedAnimalKeepers),
  ],
  data() {
    return {
      fields: {
        animalName: createField(),
        animalKeeper: createField(),
      },
      entity: {
        animalName: null,
        animalKeeper: null,
      },

      searchName: "",
      searchPostalCode: "",
    }
  },
  watch: {
    items: {
      handler: function (items) {
        if (items.length === 1) {
          this.entity.animalKeeper = items[0]
        }
      }
    }
  },
  computed: {
    query: function () {
      if (!this.searchPostalCode && !this.searchName) {
        return null
      }

      const filter = sanitizeSearchFilter({postalCode: this.searchPostalCode, name: this.searchName})
      const order = orderFilter([{property: 'postalCode', order: 'asc'}, {property: 'name', order: 'asc'}])
      return {...filter, ...order}
    },
    animalKeepers: function () {
      return this.items.map(item => ({label: formatAnimalKeeperShort(item), value: item}))
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
    addedAnimalKeeper: function (animalKeeper) {
      this.searchPostalCode = this.searchName = null // first empty to ensure afterwards reload
      this.searchPostalCode = animalKeeper.postalCode
      this.searchName = animalKeeper.name
    },
  }
}
</script>
