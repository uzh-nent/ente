<template>
  <div class="card">
    <div class="card-body">
      <actionable-preview>
        <slot/>
        <template #actions>
          <slot name="actions"/>
        </template>
      </actionable-preview>
    </div>
    <div v-if="$slots.footer" class="card-footer">
      <slot name="footer"/>
    </div>
  </div>
</template>

<script>

import ActionablePreview from "./ActionablePreview.vue";

export default {
  components: {ActionablePreview}
}

</script>
