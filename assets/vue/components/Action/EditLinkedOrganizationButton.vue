<template>
  <button-confirm-modal
      :title="$t('_action.edit_organization.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
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
    </template>
  </button-confirm-modal>
</template>

<script>

import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import OrganizationForm, {organizationFields} from "../Form/OrganizationForm.vue";
import Checkbox from "../Library/FormInput/Checkbox.vue";
import {linkedEntityEditAction} from "./utils/linkedEntity";

export default {
  emits: ['update'],
  mixins: [linkedEntityEditAction],
  components: {
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
    focusOrganization: function () {
      document.getElementById('name')?.focus()
      this.reloadReference()
    }
  }
}
</script>
