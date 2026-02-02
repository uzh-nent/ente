<template>
  <button-confirm-modal
      :title="$t('_action.edit_practitioner.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusPractitioner">
    <practitioner-form :template="practitioner" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PractitionerForm from "../Form/PractitionerForm.vue";

export default {
  emits: ['edited'],
  components: {
    PractitionerForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      patch: null,
    }
  },
  props: {
    practitioner: {
      type: Object,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return this.patch && Object.keys(this.patch).length > 0
    },
  },
  methods: {
    confirm: async function () {
      const payload = {...this.patch}
      await api.patch(this.practitioner, payload)

      const successMessage = this.$t('_action.edit_practitioner.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusPractitioner: function () {
      document.getElementById('name')?.focus()
    }
  }
}
</script>
