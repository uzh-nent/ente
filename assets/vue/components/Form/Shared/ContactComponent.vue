<template>
  <div class="d-flex gap-2 mb-3" v-if="!showEmail || !showPhone || !showContact">
    <button class="btn btn-sm btn-outline-secondary" @click="show.email = true" v-if="!showEmail">
      <i class="fas fa-plus"></i>
      {{ $t('contact.email') }}
    </button>
    <button class="btn btn-sm btn-outline-secondary" @click="show.phone = true" v-if="!showPhone">
      <i class="fas fa-plus"></i>
      {{ $t('contact.phone') }}
    </button>
    <button class="btn btn-sm btn-outline-secondary" @click="show.contact = true" v-if="!showContact">
      <i class="fas fa-plus"></i>
      {{ $t('contact.contact') }}
    </button>
  </div>


  <form-field for-id="email" :label="$t('contact.email')" :field="fields.email" v-if="showEmail">
    <text-input id="email" type="text" :field="fields.email" v-model="entity.email"
                @blur="blurField('email')" @update:modelValue="validateField('email')"/>
  </form-field>

  <form-field for-id="phone" :label="$t('contact.phone')" :field="fields.phone" v-if="showPhone">
    <text-input id="phone" type="text" :field="fields.phone" v-model="entity.phone"
                @blur="blurField('phone')" @update:modelValue="validateField('phone')"/>
  </form-field>

  <form-field for-id="contact" :label="$t('contact.contact')" :field="fields.contact" v-if="showContact">
    <text-area id="contact" :field="fields.contact" v-model="entity.contact"
               @blur="blurField('contact')" @update:modelValue="validateField('contact')"/>
  </form-field>
</template>

<script>
import {templatedForm, createField} from '../utils/form'
import FormField from '../../Library/FormLayout/FormField'
import TextArea from "../../Library/FormInput/TextArea.vue";
import TextInput from "../../Library/FormInput/TextInput.vue";

export default {
  emits: ['update'],
  components: {
    TextInput,
    TextArea,
    FormField
  },
  mixins: [templatedForm],
  data() {
    return {
      fields: {
        email: createField(),
        phone: createField(),
        contact: createField(),
      },
      entity: {
        email: null,
        phone: null,
        contact: null,
      },
      show: {
        email: false,
        phone: false,
        contact: false,
      },
    }
  },
  watch: {
    showEmail: function (newValue) {
      if (newValue) {
        this.$nextTick(() => {
          document.getElementById('email')?.focus()
        })
      }
    },
    showPhone: function (newValue) {
      if (newValue) {
        this.$nextTick(() => {
          document.getElementById('phone')?.focus()
        })
      }
    },
    showContact: function (newValue) {
      if (newValue) {
        this.$nextTick(() => {
          document.getElementById('contact')?.focus()
        })
      }
    }
  },
  computed: {
    showEmail: function () {
      return this.show.email || this.entity.email || this.template.email
    },
    showPhone: function () {
      return this.show.phone || this.entity.phone || this.template.phone
    },
    showContact: function () {
      return this.show.contact || this.entity.contact || this.template.contact
    },
  }
}

export const contactFields = ["email", "phone", "contact"]
</script>
