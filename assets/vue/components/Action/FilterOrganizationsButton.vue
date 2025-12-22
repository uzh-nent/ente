<template>
  <button-with-modal-confirm
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

  </button-with-modal-confirm>
</template>

<script>

import ButtonWithModalConfirm from '../Library/Behaviour/ButtonWithModalConfirm'
import OrganizationFilterForm from "../Form/OrganizationFilterForm.vue";

export default {
  emits: ['update'],
  components: {
    OrganizationFilterForm,
    ButtonWithModalConfirm
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
