<template>
    <form-field for-id="ordererOrg" :label="$t('animal_keeper._name')">
      <actionable-preview class="mb-2" v-if="animalKeeper">
        <animal-keeper-view :animal-keeper="animalKeeper"/>
        <template #actions>
          <edit-linked-animal-keeper-button :entity="animalKeeper" @update="animalKeeperOverride = $event" />
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
            :template="{postalCode: searchPostalCode, name: searchName}" @added="addedAnimalKeeper"/>
      </div>

      <div class="mb-2">
        <radio id="ordererOrg" :choices="animalKeepers" :value-string="value => value['@id']"
               v-model="selectedAnimalKeeper" />
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
import {formatAnimalKeeperShort} from "../../services/domain/formatter";
import AddAnimalKeeperButton from "./AddAnimalKeeperButton.vue";
import AnimalKeeperView from "../View/AnimalKeeperView.vue";
import TextInput from "../Library/FormInput/TextInput.vue";
import EditPatientButton from "./EditPatientButton.vue";
import ActionablePreview from "../Library/View/ActionablePreview.vue";
import PatientView from "../View/PatientView.vue";
import EditAnimalKeeperButton from "./EditAnimalKeeperButton.vue";
import {probeConverter} from "../../services/domain/converters";
import EditLinkedAnimalKeeperButton from "./EditLinkedAnimalKeeperButton.vue";

export default {
  emits: ['update'],
  components: {
    EditLinkedAnimalKeeperButton,
    EditAnimalKeeperButton,
    PatientView, ActionablePreview, EditPatientButton,
    TextInput,
    Radio,
    AnimalKeeperView,
    AddAnimalKeeperButton,
    FormField
  },
  mixins: [
    paginatedQuery(10, api.getPaginatedAnimalKeepers),
  ],
  data() {
    return {
      selectedAnimalKeeper: null,
      animalKeeperOverride: null,

      searchName: "",
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
    animalKeeper: function () {
      if (this.animalKeeperOverride) {
        return this.animalKeeperOverride
      }

      if (this.selectedAnimalKeeper) {
        return this.selectedAnimalKeeper
      }

      if (this.probe) {
        return probeConverter.reconstructAnimalKeeper(this.probe)
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
    animalKeepers: function () {
      return this.items.map(item => ({label: formatAnimalKeeperShort(item), value: item}))
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
          this.selectedAnimalKeeper = items[0]
        }
      }
    },
    selectedAnimalKeeper: {
      handler: function () {
        this.animalKeeperOverride = null
      }
    },
    animalKeeper: {
      handler: function (animalKeeper) {
        const orderOrg = probeConverter.copyFromAnimalKeeper(animalKeeper)
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
    addedAnimalKeeper: function (animalKeeper) {
      this.searchPostalCode = this.searchName = null // first empty to ensure afterwards reload
      this.searchPostalCode = animalKeeper.postalCode
      this.searchName = animalKeeper.name
    },
  }
}
</script>
