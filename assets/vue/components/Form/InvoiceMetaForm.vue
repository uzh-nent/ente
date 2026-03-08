<template>
  <div class="row">
    <div class="col-md-6">
      <form-field for-id="date" :label="$t('invoice.date')" :field="fields.date">
        <date-time-input id="date" :field="fields.date" v-model="entity.date" format="date"
                         @blur="blurField('date')" @update:modelValue="validateField('date')"/>
      </form-field>
    </div>
    <form-field for-id="receiver" :label="$t('invoice.receiver')"
                :field="fields.receiver">
      <radio id="receiver" :choices="receivers" :field="fields.receiver"
             v-model="entity.receiver" @update:model-value="validateField('receiver')"/>
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule} from './utils/form'
import FormField from '../Library/FormLayout/FormField'
import TextInput from '../Library/FormInput/TextInput.vue'
import DateTimeInput from '../Library/FormInput/DateTimeInput.vue'
import Radio from "../Library/FormInput/Radio.vue";

const createReceiver = function (translator) {
  const values = ['ORDERER', 'PATIENT']
  return values.map(value => ({label: translator(`invoice._receiver.${value}`), value}))
}

export default {
  emits: ['update'],
  components: {
    Radio,
    DateTimeInput,
    TextInput,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        date: createField(requiredRule),
        receiver: createField(requiredRule),
      },
      entity: {
        date: null,
        receiver: null,
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
    receivers: function () {
      return createReceiver(this.$t)
    },
  }
}
</script>
