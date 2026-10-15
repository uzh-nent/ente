<template>
  <div class="row">
    <div class="col-md-12">
      <form-field :for-id="'interpretationText-CG_MLST'" :label="$t('observation.interpretation_text_cgmlst')"
                  :field="fields.interpretationText">
        <text-input :id="'interpretationText-CG_MLST'" type="text" :field="fields.interpretationText"
                    v-model="entity.interpretationText"
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
import TextAreaWithStandardText from "../../Library/FormInput/TextAreaWithStandardText.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";

export default {
  emits: ['update'],
  components: {
    TextInput,
    Radio,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        interpretationText: createField(),
      },
      entity: {
        interpretationText: null,
      },
    }
  }
}
</script>
