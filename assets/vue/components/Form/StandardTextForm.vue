<template>
  <div>
    <form-field for-id="standardText" :label="$t('standard_text._name')" :field="standardTextField">
      <div class="d-flex flex-row reset-table-styles gap-2 mb-2">
        <input id="searchText" type="text" class="form-control mw-20"
               :placeholder="$t('_view.search_by_text')"
               v-model="searchText">
      </div>

      <div class="mb-2">
        <multiline-checkbox id="standardText" :choices="filteredStandardTextChoices" :field="standardTextField"
               :value-string="value => value['@id']" :multiline-labels="true" v-model="selectedStandardTexts" />
      </div>

    </form-field>
  </div>
</template>

<script>
import {createField, requiredRule} from './utils/form'
import FormField from '../Library/FormLayout/FormField.vue'
import Radio from "../Library/FormInput/Radio.vue";
import TextInput from "../Library/FormInput/TextInput.vue";
import MultilineCheckbox from "../Library/FormInput/MultilineCheckbox.vue";

export default {
  emits: ['update'],
  components: {
    MultilineCheckbox,
    TextInput,
    Radio,
    FormField
  },
  data() {
    return {
      selectedStandardTexts: [],
      standardTextField: createField(requiredRule),

      searchText: "",
    }
  },
  props: {
    standardTexts: {
      type: Array,
      required: true
    }
  },
  computed: {
    filteredStandardTexts: function () {
      if (!this.searchText) {
        return this.standardTexts
      }

      const query = this.searchText.toLowerCase()
      return this.standardTexts.filter(s => s.text.toLowerCase().includes(query))
    },
    filteredStandardTextChoices: function () {
      return this.filteredStandardTexts.map(item => ({label: item.text, value: item}))
    },
  },
  watch: {
    selectedStandardTexts: {
      handler: function (standardTexts) {
        if (standardTexts) {
          this.$emit('update', standardTexts.map(s => s.text).join("\n\n"))
        } else {
          this.$emit('update', null)
        }
      }
    },
    filteredStandardTexts: {
      handler: function (filteredStandardTexts) {
        if (filteredStandardTexts.length === 1) {
          this.standardText = filteredStandardTexts[0]
        }
      }
    }
  }
}
</script>
