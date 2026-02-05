<template>
  <button-confirm-modal
    :title="$t('_action.filter_probe.title')" icon="fas fa-filter" button-size="sm"
    :active="templateNonTrivial"
    :confirm-label="$t('_action.set_filter')" :can-confirm="payloadNonTrivial || templateNonTrivial" :confirm="confirm"
    :abort-label="$t('_action.reset_filter')" :can-abort="templateNonTrivial" :abort="reset">
    <probe-filter-form :template="template" @update="filter = $event" />
  </button-confirm-modal>
</template>

<script>

import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import {filterAction} from "./utils/filter";
import AnimalKeeperFilterForm from "../Form/AnimalKeeperFilterForm.vue";
import ProbeFilterForm from "../Form/ProbeFilterForm.vue";

export default {
  components: {
    ProbeFilterForm,
    AnimalKeeperFilterForm,
    ButtonConfirmModal,
  },
  mixins: [filterAction]
}
</script>
