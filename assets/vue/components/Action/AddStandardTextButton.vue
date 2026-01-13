<template>
  <button-confirm-modal
      :title="$t('_action.add_standard_text.title')" icon="fas fa-plus"
      :confirm-label="$t('_action.add')" :can-confirm="canConfirm" :confirm="confirm"
      @showing="focusTextSearch">

    <standard-text-form :standard-texts="standardTexts" @update="text = $event" />
  </button-confirm-modal>
</template>

<script>

import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import StandardTextForm from "../Form/StandardTextForm.vue";

export default {
  emits: ['add'],
  components: {
    StandardTextForm,
    ButtonConfirmModal,
  },
  props: {
    standardTexts: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      text: null,
    }
  },
  computed: {
    canConfirm: function () {
      return !!this.text
    },
  },
  methods: {
    confirm: async function () {
      this.$emit('add', this.text)
    },
    focusTextSearch: function () {
      document.getElementById('searchText')?.focus()
    }
  }
}
</script>
