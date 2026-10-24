<template>
  <button-confirm-modal
      :title="$t('_action.edit_practitioner.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusPractitioner">
    <practitioner-form :template="practitioner" @update="patch = $event"/>

    <template v-if="referencedOpenProbes.length > 0 && canConfirm">
      <hr/>
      <p class="alert alert-warning mb-0">
        {{ $t('_form.open_probes_reference_entity_automatic_update') }}
        <link-to-probes-list :probes="referencedOpenProbes"/>
      </p>
    </template>
  </button-confirm-modal>
</template>

<script>

import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import PractitionerForm from "../Form/PractitionerForm.vue";
import {api} from "../../domain/api";
import {probeConverter} from "../../domain/converters";
import LinkToProbesList from "../View/Probe/LinkToProbesList.vue";

export default {
  emits: ['edited'],
  components: {
    LinkToProbesList,
    PractitionerForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      patch: null,
      referencedOpenProbes: []
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
    focusPractitioner: async function () {
      document.getElementById('name')?.focus()

      this.referencedOpenProbes = await api.getOpenProbes({"practitioner": this.practitioner['@id']})
    },
    confirm: async function () {
      const payload = {...this.patch}
      await api.patch(this.practitioner, payload)

      const successMessage = this.$t('_action.edit_practitioner.edited')
      displaySuccess(successMessage)

      if (this.referencedOpenProbes.length > 0) {
        const probePatch = probeConverter.copyFromPractitioner(this.practitioner)
        await Promise.all(this.referencedOpenProbes.map(probe => {
          api.patch(probe, probePatch)
        }))

        const successMessage = this.$t('_action.edit_practitioner.edited_referenced_open_probes')
        displaySuccess(successMessage)
      }

      this.$emit('edited')
    }
  }
}
</script>
