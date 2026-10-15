<template>
  <button-confirm-modal
    :title="$t('_action.remove_probe.title')" icon="fas fa-trash" button-size="sm" color="danger"
    :confirm-label="$t('_action.remove')" :confirm="confirm">
    <p class="alert alert-info mb-0">{{$t('_action.remove_probe.help')}}</p>
  </button-confirm-modal>
</template>

<script>

import { displaySuccess } from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import {api} from "../../domain/api";

export default {
  emits: ['removed'],
  components: {
    ButtonConfirmModal,
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
  methods: {
    confirm: async function () {
      await api.delete(this.probe)
      this.$emit('removed')

      const successMessage = this.$t('_action.remove_probe.removed')
      displaySuccess(successMessage)
    }
  }
}
</script>
