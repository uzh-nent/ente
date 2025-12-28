<template>
  <button-confirm-modal
      :title="$t('_action.add_elm_report.title')" icon="fas fa-plus"
      :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusObservation">
    <elm-report-form
        :probe="probe" :observations="observations"
        :leading-codes="leadingCodes" :organisms="organisms" :specimens="specimens"
        @update="post = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import ElmReportForm from "../Form/ElmReportForm.vue";

export default {
  emits: ['added'],
  components: {
    ElmReportForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      post: null
    }
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
    observations: {
      type: Array,
      required: true
    },
    leadingCodes: {
      type: Array,
      required: true
    },
    organisms: {
      type: Array,
      required: true
    },
    specimens: {
      type: Array,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.post
    },
    payload: function () {
      const payload = {...this.template, ...this.post, probe: this.probe['@id']}

      if (payload.observation) {
        payload.effectiveAt = payload.observation.effectiveAt
        payload.observation = payload.observation['@id']
      }

      if (payload.leadingCode) {
        payload.leadingCode = payload.leadingCode['@id']
      }

      if (payload.organism) {
        payload.organism = payload.organism['@id']
      }

      if (payload.specimen) {
        payload.specimen = payload.specimen['@id']
      }

      return payload
    }
  },
  methods: {
    confirm: async function () {
      const elmReport = await api.postElmReport(this.payload)
      this.$emit('added', elmReport)

      const successMessage = this.$t('_action.add_elm_report.added')
      displaySuccess(successMessage)
    },
    focusObservation: function () {
      document.getElementById('observation')?.focus()
    }
  }
}
</script>
