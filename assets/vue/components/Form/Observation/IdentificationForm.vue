<template>
  <div class="row">
    <div class="col-md-12">
      <checkbox id="identificationSuccessful" :label="$t('observation.identification_successful')"
                class="mb-3"
                :field="fields.identificationSuccessful" v-model="entity.identificationSuccessful"
                @update:model-value="validateField('identificationSuccessful')"/>
    </div>
    <template v-if="entity.identificationSuccessful">
      <div class="col-md-12">
        <form-field for-id="searchOrganism" :label="$t('organism._name')" :field="fields.organism">
          <searchable-radio
              v-if="organismChoices.length > 0" id="organism"
              :choices="organismChoices" :field="fields.organism"
              :value-string="value => value['@id']"
              v-model="entity.organism" @update:model-value="validateField('organism')"/>
          <p v-else class="form-text">{{ $t('_form.observation.identification.no_organism_defined') }}</p>
        </form-field>
      </div>
      <div class="col-md-12">
        <form-field for-id="cgMLST" :label="$t('observation.cgMLST')"
                    :field="fields.cgMLST">
          <text-input id="cgMLST" :field="fields.cgMLST" v-model="entity.cgMLST"
                      @blur="blurField('cgMLST')" @update:modelValue="validateField('cgMLST')"/>
        </form-field>
      </div>
    </template>
    <div class="col-md-12">
      <form-field for-id="interpretationText" :label="$t('observation.interpretation_text')"
                  :field="fields.interpretationText">
        <text-area id="interpretationText" :field="fields.interpretationText" v-model="entity.interpretationText"
                   @blur="blurField('interpretationText')" @update:modelValue="validateField('interpretationText')"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule, countryCode} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextInput from '../../Library/FormInput/TextInput.vue'
import TextArea from '../../Library/FormInput/TextArea.vue'
import DateTimeInput from '../../Library/FormInput/DateTimeInput.vue'
import Radio from "../../Library/FormInput/Radio.vue";
import CustomSelect from "../../Library/FormInput/CustomSelect.vue";
import Checkbox from "../../Library/FormInput/Checkbox.vue";
import debounce from "lodash.debounce";
import {sortOrganisms} from "../../../services/domain/sorters";
import {formatOrganism} from "../../../services/domain/formatter";
import SearchableRadio from "../../Library/FormInput/SearchableRadio.vue";

export default {
  emits: ['update'],
  components: {
    SearchableRadio,
    Checkbox,
    CustomSelect,
    Radio,
    DateTimeInput,
    TextArea,
    TextInput,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        identificationSuccessful: createField(),

        organism: createField(),
        cgMLST: createField(),
        interpretationText: createField(),
      },
      entity: {
        identificationSuccessful: null,

        organism: null,
        cgMLST: null,
        interpretationText: null,
      },
    }
  },
  props: {
    pathogen: {
      type: String,
      required: false
    },
    organisms: {
      type: Array,
      required: true
    },
  },
  computed: {
    organismChoices: function () {
      const organisms = (this.pathogen ? this.organisms.filter(o => o.pathogen === this.pathogen) : this.organisms.filter(o => !o.pathogen))
          .filter(organism => !organism.isHidden || this.template.organism === organism)

      sortOrganisms(organisms)

      return organisms.map(o => ({label: formatOrganism(o), value: o}))
    }
  },
  watch: {
    'entity.identificationSuccessful': {
      handler: function (identificationSuccessful) {
        if (identificationSuccessful) {
          this.fields.organism.rules = [requiredRule]
        } else {
          this.searchOrganism = ''
          this.entity.organism = null
          this.fields.organism.rules = []
        }
      },
    }
  }
}
</script>
