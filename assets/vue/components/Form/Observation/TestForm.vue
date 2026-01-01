<template>
  <div class="row">
    <div class="col-md-12">
      <radio
          class="mb-3"
          :id="'interpretation-' + id"
          :choices="interpretations" :field="fields.interpretation"
          v-model="entity.interpretation" @update:model-value="validateField('interpretation')"/>
    </div>
    <div class="col-md-12">
      <form-field :for-id="'interpretationText-' + id" :label="$t('observation.interpretation_text')"
                  :field="fields.interpretationText">
        <text-area :id="'interpretationText-' + id" :field="fields.interpretationText" v-model="entity.interpretationText"
                   @blur="blurField('interpretationText')" @update:modelValue="validateField('interpretationText')"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {templatedForm, createField} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextArea from '../../Library/FormInput/TextArea.vue'
import Radio from "../../Library/FormInput/Radio.vue";

const createInterpretations = function (translator) {
  const values = ['POS', 'NEG']
  return values.map(value => ({label: translator(`observation._interpretation.${value}`), value}))
      .concat({label: translator('observation._interpretation.NONE'), value: null})
}

export default {
  emits: ['update'],
  components: {
    Radio,
    TextArea,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        interpretation: createField(),
        interpretationText: createField(),
      },
      entity: {
        interpretation: null,
        interpretationText: null,
      },
    }
  },
  props: {
    id: {
      type: String,
      required: true
    }
  },
  computed: {
    interpretations: function () {
      return createInterpretations(this.$t)
    },
  }
}
</script>
