<template>
  <button-confirm-modal
      :title="$t('_action.edit_patient.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      :abort-label="$t('_form.unlink_reference')" :abort="abort"
      @showing="focusPatient">
    <p class="alert alert-warning" v-if="referenceIsDifferent && !hasPatch && !useReference">
      {{ $t('_form.reference_is_different') }}
      <a href="#" @click="useReference = true">
        {{ $t('_form.use_reference') }}
      </a>
    </p>
    <patient-form :template="template" @update="patch = $event"/>
    <template v-slot:footer-center>
      <linked-entity-store-reference
          v-if="referenceIsDifferent && hasPatch"
          v-model="storeReference" :other-referenced-open-probes="otherReferencedOpenProbes" />
    </template>
  </button-confirm-modal>
</template>

<script>

import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import Checkbox from "../Library/FormInput/Checkbox.vue";
import {linkedEntityEditAction} from "./utils/linkedEntity";
import PatientForm, {patientFields} from "../Form/PatientForm.vue";
import LinkToProbesList from "../View/Probe/LinkToProbesList.vue";
import {api} from "../../domain/api";
import {probeConverter} from "../../domain/converters";
import LinkedEntityStoreReference from "./utils/LinkedEntityStoreReference.vue";

export default {
  mixins: [linkedEntityEditAction],
  components: {
    LinkedEntityStoreReference,
    LinkToProbesList,
    PatientForm,
    Checkbox,
    ButtonConfirmModal,
  },
  computed: {
    entityFields: function () {
      return patientFields
    },
  },
  methods: {
    focusPatient: async function () {
      document.getElementById('familyName')?.focus()
      await this.reloadReference()

      const referencedOpenProbes = await api.getOpenProbes({"patient": this.reference['@id']})
      this.otherReferencedOpenProbes = this.probe ? referencedOpenProbes.filter(p => p['@id'] !== this.probe['@id']) : referencedOpenProbes
    },
    copyFromLinkedEntity: function (linkedEntity) {
      return probeConverter.copyFromPatient(linkedEntity)
    }
  }
}
</script>
