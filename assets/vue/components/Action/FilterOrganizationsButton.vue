<template>
  <button-confirm-modal
      modalSize="lg"
      :title="$t('_action.filter.title')"
      :confirm-title="$t('_action.filter.apply')"
      @confirm="confirm"
      :abort-title="$t('_action.filter.clear')"
      :can-abort="true"
      :active="isFilterSet"
      @abort="reset">
    <template v-slot:button-content>
      <span class="text-nowrap">
        <i class="fas fa-filter"></i>
        {{ $t('_action.filter.title') }}
      </span>
    </template>

    <organization-filter-form @update="filter = $event" :template="template" :organization-types="organizationTypes"/>

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
