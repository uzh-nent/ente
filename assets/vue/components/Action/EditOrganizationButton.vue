<template>
  <button-confirm-modal
      :title="$t('_action.edit_organization.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusOrganization">
    <organization-form :template="organization" @update="patch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import OrganizationForm from "../Form/OrganizationForm.vue";

export default {
  emits: ['edited'],
  components: {
    OrganizationForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      patch: null,
    }
  },
  props: {
    organization: {
      type: Object,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.patch
    },
  },
  methods: {
    confirm: async function () {
      const payload = {...this.patch}
      await api.patch(this.organization, payload)

      const successMessage = this.$t('_action.edit_organization.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusOrganization: function () {
      document.getElementById('name')?.focus()
    }
  }
}
</script>
