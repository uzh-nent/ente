<template>
  <button-confirm-modal
    :title="$t('_action.edit_animal_keeper.title')" icon="fas fa-edit"
    button-size="sm" color="secondary"
    :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm">
    <div class="d-flex flex-column align-items-end">
      <div class="w-100">
        <animalKeeper-form :template="animalKeeper" @update="patch = $event" />
      </div>
    </div>
  </button-confirm-modal>
</template>

<script>

import { api } from '../../services/api'
import { displaySuccess } from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import AnimalKeeperForm from "../Form/AnimalKeeperForm.vue";

export default {
  components: {
    AnimalKeeperForm,
    ButtonConfirmModal,
  },
  data () {
    return {
      patch: null,
    }
  },
  props: {
    animalKeeper: {
      type: Object,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.patch
    },
  },
  methods: {
    confirm: async function () {
      const payload = { ...this.patch, organizers: this.organizers }
      await api.patch(this.animalKeeper, payload)

      const successMessage = this.$t('_action.edit_animal_keeper.edited')
      displaySuccess(successMessage)
    }
  }
}
</script>
