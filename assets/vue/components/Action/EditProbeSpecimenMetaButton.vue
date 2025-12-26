<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_specimen_meta.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusSpecimenMeta">
    <specimen-meta-form edit-mode :template="probe" @update="patch = $event" :specimens="specimens"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PatientForm from "../Form/PatientForm.vue";
import SpecimenMetaForm from "../Form/Probe/SpecimenMetaForm.vue";

export default {
  emits: ['edited'],
  components: {
    SpecimenMetaForm,
    PatientForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      patch: null,
    }
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
    specimens: {
      type: Array,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.patch
    },
    payload: function () {
      const payload = {...this.patch}
      if (payload.specimen) {
        payload.specimen = payload.specimen['@id']
      }

      return payload
    }
  },
  methods: {
    confirm: async function () {
      await api.patch(this.probe, this.payload)

      const successMessage = this.$t('_action.edit_probe_specimen_meta.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusSpecimenMeta: function () {
      document.getElementById('pathogen')?.focus()
    }
  }
}
</script>
