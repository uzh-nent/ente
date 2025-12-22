<template>
  <button-confirm-modal
      modalSize="lg"
      :title="$t('_action.filter.title')"
      :confirm-label="$t('_action.filter.apply')"
      :confirm="confirm"
      :abort-label="$t('_action.filter.clear')"
      :can-abort="true"
      :active="isFilterSet"
      :abort="reset">
    <organization-filter-form @update="filter = $event" :template="template"/>
  </button-confirm-modal>
</template>

<script>

import OrganizationFilterForm from "../Form/OrganizationFilterForm.vue";
import ButtonConfirmModal from "../Library/Behaviour/Modal/ButtonConfirmModal.vue";

export default {
  emits: ['update'],
  components: {
    ButtonConfirmModal,
    OrganizationFilterForm,
  },
  data() {
    return {
      filter: null,
    }
  },
  props: {
    template: {
      type: Object,
      required: true
    },
  },
  computed: {
    isFilterSet: function () {
      return Object.keys(this.template).some(key => this.template[key])
    }
  },
  methods: {
    reset: function () {
      this.$emit('update', {})
    },
    confirm: function () {
      this.$emit('update', this.filter)
    }
  }
}
</script>
