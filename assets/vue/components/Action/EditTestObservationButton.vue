<template>
  <button-confirm-modal
      :title="$t('_action.edit_test_observation.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusInterpretation">
    <test-shared-form :template="observation" @update="sharedPatch = $event"/>
    <test-form :id="observation.analysisType" :template="observation" @update="testPatch = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import TestForm from "../Form/Observation/TestForm.vue";
import TestSharedForm from "../Form/Observation/TestSharedForm.vue";

export default {
  emits: ['edited'],
  components: {
    TestSharedForm,
    TestForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      sharedPatch: null,
      testPatch: null,
    }
  },
  props: {
    observation: {
      type: Object,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.payload
    },
    payload: function () {
      return {...this.sharedPatch, ...this.testPatch}
    }
  },
  methods: {
    confirm: async function () {
      await api.patch(this.observation, this.payload)

      const successMessage = this.$t('_action.edit_test_observation.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusInterpretation: function () {
      document.getElementById('interpretation-' + this.observation.analysisType)?.focus()
    }
  },
}
</script>
