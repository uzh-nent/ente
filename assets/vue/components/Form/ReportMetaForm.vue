<template>
  <div class="row">
    <div class="col-md-6">
      <form-field for-id="date" :label="$t('report.date')" :field="fields.date">
        <date-time-input id="date" :field="fields.date" v-model="entity.date" format="date"
                         @blur="blurField('date')" @update:modelValue="validateField('date')"/>
      </form-field>
    </div>
    <div class="col-md-6">
      <form-field
          for-id="claimCertification" :label="$t('report.claim_certification')" :field="fields.claimCertification">
        <checkbox id="claimCertification" :field="fields.claimCertification"
                  v-model="entity.claimCertification" @update:model-value="validateField('claimCertification')"/>
      </form-field>
    </div>
    <div class="col-md-12">
      <form-field
          for-id="predefinedTitle" :label="$t('report.title')" :field="fields.predefinedTitle" :fake-required="true">
        <custom-select id="predefinedTitle" :choices="predefinedTitles" :field="fields.predefinedTitle"
                       v-model="entity.predefinedTitle" @update:model-value="validateField('predefinedTitle')"/>
        <text-input v-if="!entity.predefinedTitle" class="mt-1"
                    id="customTitle" type="text" :field="fields.customTitle"
                    v-model="entity.customTitle"
                    @blur="blurField('customTitle')" @update:modelValue="validateField('customTitle')"/>
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
import CustomSelect from "../Library/FormInput/CustomSelect.vue";
import Checkbox from "../Library/FormInput/Checkbox.vue";
import IdentificationView from "../View/Observation/IdentificationView.vue";
import ActionablePreview from "../Library/View/ActionablePreview.vue";
import {formatObservation} from "../../services/domain/formatter";
import SearchableSelect from "../Library/FormInput/SearchableSelect.vue";
import TestView from "../View/Observation/TestView.vue";

const createPredefinedTitles = function (translator) {
  const values = ['INTERMEDIATE', 'FINAL', 'ADDENDUM', 'CORRECTION']
  return values.map(value => ({label: translator(`report._predefined_title.${value}`), value}))
      .concat({label: translator('_form.other'), value: null})
}

export default {
  emits: ['update'],
  components: {
    TestView,
    SearchableSelect,
    ActionablePreview,
    IdentificationView,
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
        date: createField(requiredRule),
        claimCertification: createField(),
        predefinedTitle: createField(requiredRule),
        customTitle: createField(),
      },
      entity: {
        date: null,
        claimCertification: null,
        predefinedTitle: null,
        customTitle: null,
      },
    }
  },
  props: {
    probe: {
      type: Object,
      required: false
    },
  },
  computed: {
    predefinedTitles: function () {
      return createPredefinedTitles(this.$t)
    },
  },
  watch: {
    'entity.predefinedTitle': {
      handler: function (predefinedTitle) {
        this.fields.customTitle.rules = !predefinedTitle ? [requiredRule] : []
        this.fields.predefinedTitle.rules = predefinedTitle ? [requiredRule] : []
      }
    }
  }
}
</script>
