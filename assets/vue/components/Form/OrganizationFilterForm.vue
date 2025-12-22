<template>
  <div class="row mt-3">
    <div class="col-md-6">
      <date-range-field
          :field-name="$t('customer.createdAt')"
          v-model:from="filter['createdAt[after]']" v-model:until="filter['createdAt[before]']"/>
    </div>

    <div class="col-md-6">
      <date-range-field
          :field-name="$t('customer.updatedAt')"
          v-model:from="filter['updatedAt[after]']" v-model:until="filter['updatedAt[before]']"/>
    </div>
  </div>
</template>

<script>
import TimeFilter from "../Library/Form/TimeFilter";
import DateRangeField from "./Fields/DateRangeField.vue";

export default {
  components: {DateRangeField, TimeFilter},
  emits: ['update'],
  data() {
    return {
      filter: {
        "name": null,
        "postalCode": null,

        'createdAt[before]': null,
        'createdAt[after]': null,

        'updatedAt[before]': null,
        'updatedAt[after]': null
      }
    }
  },
  props: {
    template: {
      type: Object,
      required: false
    },
  },
  watch: {
    filter: {
      deep: true,
      handler: function () {
        this.$emit('update', this.filter)
      }
    }
  },
  mounted() {
    this.filter = Object.assign({}, this.filter, this.template)
  }
}
</script>
