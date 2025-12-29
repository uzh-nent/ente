<template>
  <button-confirm-modal
    :title="$t('_action.add_practitioner.title')" icon="fas fa-plus"
    :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
    @showing="focusPractitioner">
    <practitioner-form :template="extendedTemplate" @update="post = $event" />
  </button-confirm-modal>
</template>

<script>

import { api } from '../../services/api'
import { displaySuccess } from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PractitionerForm from "../Form/PractitionerForm.vue";

export default {
  emits: ['added'],
  components: {
    PractitionerForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  props: {
    template: {
      type: Object,
      default: {}
    },
  },
  data () {
    return {
      post: null
    }
  },
  computed: {
    canConfirm: function () {
      return !!this.post
    },
    extendedTemplate: function () {
      return {
        ...this.template,
        countryCode: 'CH',
      }
    }
  },
  methods: {
    confirm: async function () {
      const payload = { ...this.extendedTemplate, ...this.post }
      const practitioner = await api.postPractitioner(payload)
      this.$emit('added', practitioner)

      const successMessage = this.$t('_action.add_practitioner.added')
      displaySuccess(successMessage)
    },
    focusPractitioner: function () {
      document.getElementById('title')?.focus()
    }
  },
}
</script>
