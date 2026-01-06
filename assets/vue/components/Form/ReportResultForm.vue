<template>
  <div>
    <h3>{{ template.analysis }}</h3>
    <div class="row">
      <div class="col-md-12">
        <form-field for-id="result" :label="$t('report.result')" :field="fields.result">
          <text-input id="result" type="text" :field="fields.result"
                      v-model="entity.result"
                      @blur="blurField('result')" @update:modelValue="validateField('result')"/>
        </form-field>

        <form-field for-id="comment" :label="$t('report.comment')" :field="fields.comment">
          <text-area id="comment" type="text" :field="fields.comment"
                     v-model="entity.comment"
                     @blur="blurField('comment')" @update:modelValue="validateField('comment')"/>
        </form-field>
      </div>
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
        result: createField(requiredRule),
        comment: createField(),
      },
      entity: {
        result: null,
        comment: null,
      },
    }
  },
}
</script>
