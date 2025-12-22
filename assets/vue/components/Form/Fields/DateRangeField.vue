<template>
  <div>
    <label class="form-label">{{ fieldName }}</label>
    <div class="row g-1">
      <div class="col-6">
        <div class="input-group">
          <div class="input-group-text">{{ $t('_form.timeFilter.from') }}</div>
          <input type="date" class="form-control" :value="from" @change="$emit('update:from', $event.target.value)">
        </div>
      </div>
      <div class="col-6">
        <div class="input-group">
          <div class="input-group-text ms-2">{{ $t('_form.timeFilter.until') }}</div>
          <input type="date" class="form-control" :value="until" @change="$emit('update:until', $event.target.value)">
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  emits: ['update:from', 'update:until'],
  props: {
    fieldName: {
      type: String,
      required: false
    },
    from: {
      type: String,
      required: false
    },
    until: {
      type: String,
      required: false
    }
  }
}
</script>
