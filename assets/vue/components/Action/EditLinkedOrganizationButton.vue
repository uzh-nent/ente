<template>
  <button-confirm-modal
      :title="$t('_action.edit_organization.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      :abort-label="$t('_form.unlink_reference')" :abort="abort"
      @showing="focusOrganization">
    <p class="alert alert-warning" v-if="referenceIsDifferent && !hasPatch && !useReference">
      {{ $t('_form.reference_is_different') }}
      <a href="#" @click="useReference = true">
        {{ $t('_form.use_reference') }}
      </a>
    </p>
    <organization-form :template="template" @update="patch = $event"/>
    <template v-slot:footer-center>
      <checkbox v-if="referenceIsDifferent"
                id="storeReference" :label="$t('_form.store_reference')"
                v-model="storeReference"/>
      <p v-if="referenceIsDifferent && storeReference && otherReferencedOpenProbes.length > 0" class="alert alert-warning">
        {{ $t('_form.other_open_probes_reference_entity')}}
        <link-to-probes-list :probes="otherReferencedOpenProbes" />
      </p>
    </template>
  </button-confirm-modal>
</template>

<script>

import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import OrganizationForm, {organizationFields} from "../Form/OrganizationForm.vue";
import Checkbox from "../Library/FormInput/Checkbox.vue";
import {linkedEntityEditAction} from "./utils/linkedEntity";
import {api} from "../../services/api";
import LinkToProbesList from "../View/Probe/LinkToProbesList.vue";

export default {
  mixins: [linkedEntityEditAction],
  components: {
    LinkToProbesList,
    Checkbox,
    OrganizationForm,
    ButtonConfirmModal,
  },
  computed: {
    entityFields: function () {
      return organizationFields
    },
  },
  methods: {
    focusOrganization: async function () {
      document.getElementById('name')?.focus()
      await this.reloadReference()

      const referencedOpenProbes = await api.getOpenProbes({"ordererOrg": this.reference['@id']})
      this.otherReferencedOpenProbes = this.probe ? referencedOpenProbes.filter(p => p['@id'] !== this.probe['@id']) : referencedOpenProbes
    }
  }
}
</script>
