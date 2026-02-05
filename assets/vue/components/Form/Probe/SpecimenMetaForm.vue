<template>
  <div>
    <form-field for-id="specimenCollectionDate" :label="$t('probe.specimen_collection_date')" :field="fields.specimenCollectionDate">
      <date-time-input id="specimenCollectionDate" :field="fields.specimenCollectionDate" v-model="entity.specimenCollectionDate" format="date"
                       @blur="blurField('specimenCollectionDate')" @update:modelValue="validateField('specimenCollectionDate')"/>
    </form-field>

    <!-- select specimen source -->
    <div class="row">
      <div class="col-md-12">
        <form-field for-id="specimenSource" :label="$t('probe.specimen_source')" :field="fields.specimenSource" :fake-required="true">
          <custom-select id="specimenSource" :choices="specimenSources" :field="fields.specimenSource" :disabled="editMode"
                         v-model="entity.specimenSource" @update:model-value="validateField('specimenSource')"/>
          <text-input v-if="!entity.specimenSource" class="mt-1"
                      id="specimenSourceText" type="text" :field="fields.specimenSourceText"
                      v-model="entity.specimenSourceText"
                      @blur="blurField('specimenSourceText')" @update:modelValue="validateField('specimenSourceText')"/>

          <template v-else-if="entity.specimenSource === 'FOOD'">
            <custom-select id="specimenFoodType" class="mt-1" :choices="specimenFoodTypes" :field="fields.specimenFoodType"
                           v-model="entity.specimenFoodType" @update:model-value="validateField('specimenFoodType')"/>
            <text-input v-if="!entity.specimenFoodType" class="mt-1"
                        id="specimenTypeText" type="text" :field="fields.specimenTypeText"
                        v-model="entity.specimenTypeText"
                        @blur="blurField('specimenTypeText')" @update:modelValue="validateField('specimenTypeText')"/>
          </template>
          <template v-else-if="entity.specimenSource === 'ANIMAL'">
            <custom-select id="specimenAnimalType" class="mt-1" :choices="specimenAnimalTypes" :field="fields.specimenAnimalType"
                           v-model="entity.specimenAnimalType" @update:model-value="validateField('specimenAnimalType')"/>
            <text-input v-if="!entity.specimenAnimalType" class="mt-1"
                        id="specimenTypeText" type="text" :field="fields.specimenTypeText"
                        v-model="entity.specimenTypeText"
                        @blur="blurField('specimenTypeText')" @update:modelValue="validateField('specimenTypeText')"/>
          </template>
          <template v-else-if="entity.specimenSource !== 'HUMAN'">
            <text-input class="mt-1"
                        id="specimenTypeText" type="text" :field="fields.specimenTypeText"
                        v-model="entity.specimenTypeText"
                        @blur="blurField('specimenTypeText')" @update:modelValue="validateField('specimenTypeText')"/>
          </template>
        </form-field>
      </div>
    </div>

    <!-- select specimen -->
    <template v-if="entity.specimenSource === 'HUMAN'">
      <div class="row">
        <div class="col-md-9">
          <form-field for-id="specimen" :label="$t('specimen._name')" :field="fields.specimen">
            <custom-select id="specimen" :choices="specimenChoices" :field="fields.specimen"
                           v-model="entity.specimen" @update:model-value="validateField('specimen')">
              <option :key="null" :value="null">
                {{ $t('_form.other_or_unknown') }}
              </option>
            </custom-select>
          </form-field>
        </div>
        <div class="col-md-3">
          <form-field
              for-id="specimenIsolate" :label="$t('probe.specimen_isolate')" :field="fields.specimenIsolate">
            <checkbox id="specimenIsolate" :field="fields.specimenIsolate"
                           v-model="entity.specimenIsolate" @update:model-value="validateField('specimenIsolate')"/>
          </form-field>
        </div>
        <div class="col-md-9 shift-input-up" v-if="!entity.specimen">
          <text-input id="specimenText" type="text" :field="fields.specimenText"
                      v-model="entity.specimenText"
                      @blur="blurField('specimenText')" @update:modelValue="validateField('specimenText')"/>
        </div>
      </div>
    </template>
    <form-field v-if="entity.specimenSource !== 'HUMAN'"
                for-id="specimenText" :label="$t('probe.specimen_text')" :field="fields.specimenText">
      <text-input id="specimenText" type="text" :field="fields.specimenText"
                  v-model="entity.specimenText"
                  @blur="blurField('specimenText')" @update:modelValue="validateField('specimenText')"/>
    </form-field>

    <!-- select specimen location -->
    <form-field v-if="entity.specimenSource !== 'HUMAN' && entity.specimenSource !== 'ANIMAL'"
                for-id="specimenLocation" :label="$t('probe.specimen_location')" :field="fields.specimenLocation">
      <text-area id="specimenLocation" type="text" :field="fields.specimenLocation"
                  v-model="entity.specimenLocation"
                  @blur="blurField('specimenLocation')" @update:modelValue="validateField('specimenLocation')"/>
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField.vue'
import TextInput from '../../Library/FormInput/TextInput.vue'
import TextArea from '../../Library/FormInput/TextArea.vue'
import DateTimeInput from '../../Library/FormInput/DateTimeInput.vue'
import Radio from "../../Library/FormInput/Radio.vue";
import Checkboxes from "../../Library/FormInput/Checkboxes.vue";
import {paginatedQuery} from "../../View/utils/table";
import {api} from "../../../services/api";
import AddOrganizationButton from "../../Action/AddOrganizationButton.vue";
import OrganizationView from "../../View/OrganizationView.vue";
import CustomSelect from "../../Library/FormInput/CustomSelect.vue";
import Checkbox from "../../Library/FormInput/Checkbox.vue";

const createSpecimenSource = function (translator) {
  const values = ['HUMAN', 'ANIMAL', 'FOOD', 'FEED', 'ENVIRONMENT', 'LABORATORY_STRAIN']
  return values.map(value => ({label: translator(`probe._specimen_source.${value}`), value}))
      .concat({label: translator('_form.other_or_unknown'), value: null})
}

const createSpecimenFoodTypes = function (translator) {
  const values = ['POULTRY', 'MEAT', 'DAIRY', 'EGG', 'FISH']
  return values.map(value => ({label: translator(`probe._specimen_food_type.${value}`), value}))
      .concat({label: translator('_form.other_or_unknown'), value: null})
}

const createSpecimenAnimalTypes = function (translator) {
  const values = ['CATTLE', 'PIG', 'CHICKEN', 'BIRD', 'REPTILIAN']
  return values.map(value => ({label: translator(`probe._specimen_animal_type.${value}`), value}))
      .concat({label: translator('_form.other_or_unknown'), value: null})
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
      required: true
    },
    editMode: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      fields: {
        specimenCollectionDate: createField(),
        specimenSource: createField(),
        specimenSourceText: createField(),
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
        specimenCollectionDate: null,
        specimenSource: null,
        specimenSourceText: null,
        specimenText: null,

        specimenTypeText: null,
        specimenLocation: null,

        specimenFoodType: null,
        specimenAnimalType: null,

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
      console.log(this.template.specimen, this.specimens)
      return this.specimens
          .filter(specimen => !specimen.isHidden || this.template.specimen === specimen)
          .map(specimen => ({label: specimen.displayName, value: specimen}))
    },
  },
  watch: {
    'entity.specimen': {
      handler: function (specimen) {
        if (specimen) {
          this.entity.specimenText = null
        }
      }
    },
    'entity.specimenSource': {
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
        } else {
          this.entity.specimenAnimalType = this.specimenAnimalTypes[0].value
        }

        if (specimenSource !== 'HUMAN') {
          this.entity.specimen = null
          this.entity.specimenIsolate = null
          this.fields.specimen.rules = []
        } else {
          this.entity.specimenIsolate = true
          this.entity.specimen = this.specimens.find(specimen => specimen.displayName.includes("Stool"))
          this.fields.specimen.rules = [requiredRule]
        }
      },
    }
  }
}
</script>

<style scoped>
.shift-input-up {
  margin-top: -0.8rem;
}
</style>
