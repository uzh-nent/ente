<template>
  <button-confirm-modal
      :title="$t('_action.edit_probe_orderer.title')" icon="fas fa-edit"
      button-size="sm" color="secondary"
      :confirm-label="$t('_action.edit')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusOrderer">
    <animal-name-form class="mw-20" :template="probe" @update="animalName = $event"/>
    <set-probe-animal-keeper-view :probe="probe" @update="animalKeeper = $event"/>
  </button-confirm-modal>
</template>

<script>

import {api} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import SetProbeAnimalKeeperView from "./SetProbeAnimalKeeperView.vue";
import AnimalNameForm from "../Form/Probe/AnimalNameForm.vue";

export default {
  emits: ['edited'],
  components: {
    AnimalNameForm,
    SetProbeAnimalKeeperView,
    ButtonConfirmModal,
  },
  data() {
    return {
      animalName: null,
      animalKeeper: null,
    }
  },
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
  computed: {
    canConfirm: function () {
      return Object.keys(this.payload).length > 0
    },
    payload: function () {
      let payload = {}
      if (this.animalName) {
        payload = {...payload, ...this.animalName}
      }
      if (this.animalKeeper) {
        payload = {...payload, ...this.animalKeeper}
      }

      return payload
    }
  },
  methods: {
    confirm: async function () {
      await api.patch(this.probe, this.payload)

      const successMessage = this.$t('_action.edit_probe_animal_keeper.edited')
      displaySuccess(successMessage)

      this.$emit('edited')
    },
    focusOrderer: function () {
      document.getElementById('animalName')?.focus()
    }
  }
}
</script>
