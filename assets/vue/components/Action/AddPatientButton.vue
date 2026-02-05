<template>
  <button-confirm-modal
    :title="$t('_action.add_patient.title')" icon="fas fa-plus"
    :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
    @showing="focusPatient">
    <patient-form :template="extendedTemplate" @update="post = $event" />
  </button-confirm-modal>
</template>

<script>

import { api } from '../../services/api'
import { displaySuccess } from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";

export default {
  emits: ['added'],
  components: {
    PatientForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data () {
    return {
      post: null
    }
  },
  props: {
    template: {
      type: Object,
      default: {}
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.post
    },
    extendedTemplate: function () {
      return {
        ...this.template,
        gender: 'MALE',
        countryCode: 'CH',
      }
    },
  },
  methods: {
    confirm: async function () {
      const payload = { ...this.extendedTemplate, ...this.post }
      const patient = await api.postPatient(payload)
      this.$emit('added', patient)

      const successMessage = this.$t('_action.add_patient.added')
      displaySuccess(successMessage)
    },
    focusPatient: function () {
      document.getElementById('familyName')?.focus()
    }
  }
}
</script>
