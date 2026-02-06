<template>
  <button-confirm-modal
      :title="$t('_action.edit_practitioner.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusPractitioner">
    <p class="alert alert-warning" v-if="referenceIsDifferent && !hasPatch && !useReference">
      {{ $t('_form.reference_is_different') }}
      <a href="#" @click="useReference = true">
        {{ $t('_form.use_reference') }}
      </a>
    </p>
    <practitioner-form :template="template" @update="patch = $event"/>
    <template v-slot:footer-center>
      <checkbox v-if="referenceIsDifferent"
                id="storeReference" :label="$t('_form.store_reference')"
                v-model="storeReference"/>
    </template>
  </button-confirm-modal>
</template>

<script>

import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import Checkbox from "../Library/FormInput/Checkbox.vue";
import {linkedEntityEditAction} from "./utils/linkedEntity";
import PractitionerForm, {practitionerFields} from "../Form/PractitionerForm.vue";

export default {
  emits: ['update'],
  mixins: [linkedEntityEditAction],
  components: {
    PractitionerForm,
    Checkbox,
    ButtonConfirmModal,
  },
  computed: {
    entityFields: function () {
      return practitionerFields
    },
  },
  methods: {
    focusPractitioner: function () {
      document.getElementById('familyName')?.focus()
      this.reloadReference()
    }
  }
}
</script>
