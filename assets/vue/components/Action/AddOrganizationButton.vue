<template>
  <button-confirm-modal
    :title="$t('_action.add_organization.title')" icon="fas fa-plus"
    :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm">
    <organization-form :template="extendedTemplate" @update="post = $event" />
  </button-confirm-modal>
</template>

<script>

import { api } from '../../services/api'
import { displaySuccess } from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import OrganizationForm from "../Form/OrganizationForm.vue";

export default {
  emits: ['added'],
  components: {
    OrganizationForm,
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
      const payload = { ...this.template, ...this.post }
      const organization = await api.postOrganization(payload)
      this.$emit('added', organization)

      const successMessage = this.$t('_action.add_organization.added')
      displaySuccess(successMessage)
    }
  }
}
</script>
