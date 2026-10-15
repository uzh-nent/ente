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
      <linked-entity-store-reference
          v-if="referenceIsDifferent && hasPatch"
          v-model="storeReference" :other-referenced-open-probes="otherReferencedOpenProbes" />
    </template>
  </button-confirm-modal>
</template>

<script>

import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import OrganizationForm, {organizationFields} from "../Form/OrganizationForm.vue";
import Checkbox from "../Library/FormInput/Checkbox.vue";
import {linkedEntityEditAction} from "./utils/linkedEntity";
import LinkToProbesList from "../View/Probe/LinkToProbesList.vue";
import {api} from "../../domain/api";
import {probeConverter} from "../../domain/converters";
import LinkedEntityStoreReference from "./utils/LinkedEntityStoreReference.vue";

export default {
  mixins: [linkedEntityEditAction],
  components: {
    LinkedEntityStoreReference,
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
    },
    copyFromLinkedEntity: function (linkedEntity) {
      return probeConverter.copyFromOrganization(linkedEntity)
    }
  }
}
</script>
