<template>
  <button-confirm-modal
      :title="$t('_action.edit_organization.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusOrganization">
    <organization-form :template="organization" @update="patch = $event"/>

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
import OrganizationForm from "../Form/OrganizationForm.vue";
import {api} from "../../domain/api";
import {probeConverter} from "../../domain/converters";
import LinkToProbesList from "../View/Probe/LinkToProbesList.vue";
import Checkbox from "../Library/FormInput/Checkbox.vue";

export default {
  emits: ['edited'],
  components: {
    Checkbox, LinkToProbesList,
    OrganizationForm,
    ButtonConfirmModal,
  },
  data() {
    return {
      patch: null,
      referencedOpenProbes: []
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
      return this.patch && Object.keys(this.patch).length > 0
    },
  },
  methods: {
    focusOrganization: async function () {
      document.getElementById('name')?.focus()

      this.referencedOpenProbes = await api.getOpenProbes({"ordererOrg": this.organization['@id']})
    },
    confirm: async function () {
      const payload = {...this.patch}
      await api.patch(this.organization, payload)
      const successMessage = this.$t('_action.edit_organization.edited')
      displaySuccess(successMessage)

      if (this.referencedOpenProbes.length > 0) {
        const probePatch = probeConverter.copyFromOrganization(this.organization)
        await Promise.all(this.referencedOpenProbes.map(probe => {
          api.patch(probe, probePatch)
        }))

        const successMessage = this.$t('_action.edit_organization.edited_referenced_open_probes')
        displaySuccess(successMessage)
      }

      this.$emit('edited')
    }
  }
}
</script>
