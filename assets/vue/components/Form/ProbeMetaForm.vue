<template>
  <div>
    <form-field for-id="specimenDate" :label="$t('probe.received_at')" :field="fields.specimenDate">
      <date-time-input id="specimenDate" :field="fields.specimenDate" v-model="entity.specimenDate" format="date"
                       @blur="blurField('specimenDate')" @update:modelValue="validateField('specimenDate')"/>
    </form-field>

    <!-- select specimen source -->
    <form-field for-id="specimenSource" :label="$t('probe.specimen_source')" :field="fields.specimenSource">
      <custom-select id="specimenSource" :choices="specimenSources" :field="fields.specimenSource"
                     v-model="entity.specimenSource" @update:model-value="validateField('specimenSource')"/>
    </form-field>

    <form-field v-if="!entity.specimenSource"
                for-id="specimenSourceText" :label="$t('probe.specimen_source_text')"
                :field="fields.specimenSourceText">
      <text-input id="specimenSourceText" type="text" :field="fields.specimenSourceText"
                  v-model="entity.specimenSourceText"
                  @blur="blurField('specimenSourceText')" @update:modelValue="validateField('specimenSourceText')"/>
    </form-field>

    <!-- select specimen -->
    <template v-if="entity.specimenSource === 'HUMAN'">
      <div class="row">
        <div class="col-md-9">
          <form-field
              for-id="specimen" :label="$t('probe.specimen')" :field="fields.specimen">
            <custom-select id="specimen" :choices="specimenChoices" :field="fields.specimen"
                           v-model="entity.specimen" @update:model-value="validateField('specimen')"/>
          </form-field>
        </div>
        <div class="col-md-3">
          <form-field
              for-id="specimen_isolate" :label="$t('probe.specimen_isolate')" :field="fields.specimenIsolate">
            <checkbox id="specimen_isolate" :field="fields.specimenIsolate"
                           v-model="entity.specimenIsolate" @update:model-value="validateField('specimenIsolate')"/>
          </form-field>
        </div>
      </div>
    </template>

    <form-field v-if="!entity.specimen"
                for-id="specimenText" :label="$t('probe.specimen_source_text')" :field="fields.specimenText">
      <text-input id="specimenText" type="text" :field="fields.specimenText"
                  v-model="entity.specimenText"
                  @blur="blurField('specimenText')" @update:modelValue="validateField('specimenText')"/>
    </form-field>


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
import AddOrganizationButton from "../Action/AddOrganizationButton.vue";
import OrganizationView from "../View/OrganizationView.vue";
import CustomSelect from "../Library/FormInput/CustomSelect.vue";
import Checkbox from "../Library/FormInput/Checkbox.vue";

const createSpecimenSource = function (translator) {
  const values = ['REFERENCE', 'HUMAN', 'ANIMAL', 'FOOD', 'FEED', 'ENVIRONMENT', 'LABORATORY_STRAIN']
  return values.map(value => ({label: translator(`probe._specimen_source.${value}`), value}))
      .concat({label: translator('probe._specimen_source.OTHER'), value: null})
}

const createSpecimenFoodTypes = function (translator) {
  const values = ['POULTRY', 'MEAT', 'DAIRY', 'EGG', 'FISH']
  return values.map(value => ({label: translator(`probe._specimen_food_type.${value}`), value}))
      .concat({label: translator('probe._specimen_food_type.OTHER'), value: null})
}

const createSpecimenAnimalTypes = function (translator) {
  const values = ['CATTLE', 'PIG', 'CHICKEN', 'BIRD', 'REPTILIAN']
  return values.map(value => ({label: translator(`probe._specimen_animal_type.${value}`), value}))
      .concat({label: translator('probe._specimen_animal_type.OTHER'), value: null})
}

export default {
  emits: ['update'],
  components: {
    Checkbox,
    CustomSelect,
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
    paginatedQuery(10, api.getPaginatedPatients),
  ],
  props: {
    specimens: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      fields: {
        specimenDate: createField(),
        specimenSource: createField(requiredRule),
        specimenSourceText: createField(requiredRule),
        specimenText: createField(),

        specimenTypeText: createField(),
        specimenLocation: createField(),

        specimenFoodType: createField(),

        specimenAnimalType: createField(),
        animalKeeper: createField(),
        animalName: createField(),

        patient: createField(),
        specimen: createField(),
        specimenIsolate: createField(),
      },
      entity: {
        specimenDate: null,
        specimenSource: null,
        specimenSourceText: null,
        specimenText: null,

        specimenTypeText: null,
        specimenLocation: null,

        specimenFoodType: null,

        specimenAnimalType: null,
        animalKeeper: null,
        animalName: null,

        patient: null,
        specimen: null,
        specimenIsolate: null,
      },
    }
  },
  computed: {
    specimenSources: function () {
      return createSpecimenSource(this.$t)
    },
    specimenFoodTypes: function () {
      return createSpecimenFoodTypes(this.$t)
    },
    specimenAnimalTypes: function () {
      return createSpecimenAnimalTypes(this.$t)
    },
    specimenChoices: function () {
      return this.specimens.map(specimen => ({label: specimen.displayName, value: specimen}))
    },
  },
  watch: {
    'entity.specimen': {
      immediate: true,
      handler: function (specimen) {
        if (specimen) {
          this.entity.specimenText = null
        }
      }
    },
    'entity.specimenSource': {
      immediate: true,
      handler: function (specimenSource) {
        if (specimenSource) {
          this.entity.specimenSourceText = null
        }

        if (specimenSource !== 'LABORATORY_STRAIN' && specimenSource !== 'FEED' && specimenSource !== 'ENVIRONMENT') {
          this.entity.specimenTypeText = null
          this.entity.specimenLocation = null
        }

        if (specimenSource !== 'FOOD') {
          this.entity.specimenFoodType = null
        } else {
          this.entity.specimenFoodType = this.specimenFoodTypes[0].value
        }

        if (specimenSource !== 'ANIMAL') {
          this.entity.specimenAnimalType = null
          this.entity.animalKeeper = null
          this.entity.animalName = null
        } else {
          this.entity.specimenAnimalType = this.specimenAnimalTypes[0].value
        }

        if (specimenSource !== 'HUMAN') {
          this.entity.patient = null
          this.entity.specimen = null
          this.entity.specimenIsolate = null
        } else {
          this.entity.specimenIsolate = true
          this.entity.specimen = this.specimens.find(specimen => specimen.displayName.includes("Stool"))
        }
      },
    }
  }
}
</script>
