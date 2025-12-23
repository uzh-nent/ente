<template>
  <button-confirm-modal
    :title="$t('_action.add_animal_keeper.title')" icon="fas fa-plus"
    :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm">
    <animalKeeper-form :template="template" @update="post = $event" />
  </button-confirm-modal>
</template>

<script>

import { api } from '../../services/api'
import { displaySuccess } from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import AnimalKeeperForm from "../Form/AnimalKeeperForm.vue";

export default {
  emits: ['added'],
  components: {
    AnimalKeeperForm,
    ButtonConfirmModal,
    LoopingRhombusSpinner,
  },
  data () {
    return {
      post: null
    }
  },
  computed: {
    canConfirm: function () {
      return !!this.post
    },
    template: function () {
      return {
        countryCode: 'CH',
      }
    }
  },
  methods: {
    confirm: async function () {
      const payload = { ...this.template, ...this.post }
      const animalKeeper = await api.postAnimalKeeper(payload)
      this.$emit('added', animalKeeper)

      const successMessage = this.$t('_action.add_animal_keeper.added')
      displaySuccess(successMessage)
    }
  }
}
</script>
