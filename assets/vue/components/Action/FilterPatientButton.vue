<template>
  <button-confirm-modal
    :title="$t('_action.filter.title')" icon="fas fa-filter"
    :active="payloadNonTrivial"
    :confirm-label="$t('_action.set_filter')" :confirm="confirm"
    :abort-label="$t('_action.reset_filter')" :can-abort="payloadNonTrivial" :abort="reset"
    @showing="focusPatient">
    <patient-filter-form :template="template" @update="filter = $event" />
  </button-confirm-modal>
</template>

<script>

import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientFilterForm from "../Form/PatientFilterForm.vue";
import {sanitizeFilter} from "../../services/query";

export default {
  emits: ['filtered'],
  components: {
    PatientFilterForm,
    ButtonConfirmModal,
  },
  data () {
    return {
      filter: null
    }
  },
  props: {
    template: {
      type: Object,
      required: false
    },
  },
  computed: {
    payload: function () {
      const payload = { ...this.template, ...this.filter }
      return sanitizeFilter(payload)
    },
    payloadNonTrivial: function () {
      return Object.keys(this.template ?? {}).length > 0
    }
  },
  methods: {
    confirm: async function () {
      this.$emit('filtered', {...this.payload})
    },
    reset: async function () {
      this.$emit('filtered', {})
    },
    focusPatient: function () {
      document.getElementById('familyName')?.focus()
    }
  }
}
</script>
