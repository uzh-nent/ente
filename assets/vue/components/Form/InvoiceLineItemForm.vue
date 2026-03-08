<template>
  <div class="row">
    <div class="col-md-12">
      <form-field for-id="service" :label="$t('invoice.line_item.service')" :field="fields.service">
        <text-input id="service" type="text" :field="fields.service"
                    v-model="entity.service"
                    @blur="blurField('service')" @update:modelValue="validateField('service')"/>
      </form-field>
    </div>
    <div class="col-md-6">
      <form-field for-id="position" :label="$t('invoice.line_item.position')" :field="fields.position">
        <text-input id="position" type="text" :field="fields.position"
                    v-model="entity.position"
                    @blur="blurField('position')" @update:modelValue="validateField('position')"/>
      </form-field>
    </div>
    <div class="col-md-6">
      <form-field for-id="taxpoints" :label="$t('invoice.line_item.tp')" :field="fields.tp">
        <text-input id="tp" type="number" :field="fields.tp"
                    v-model="entity.tp"
                    @blur="blurField('tp')" @update:modelValue="validateField('tp')"/>
      </form-field>
    </div>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from './utils/form'
import FormField from '../Library/FormLayout/FormField'
import TextInput from '../Library/FormInput/TextInput.vue'


export default {
  emits: ['update'],
  components: {
    TextInput,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        service: createField(requiredRule),
        position: createField(requiredRule),
        tp: createField(requiredRule),
      },
      entity: {
        service: null,
        position: null,
        tp: null,
      },
    }
  }
}
</script>
