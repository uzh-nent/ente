<template>
  <div class="card">
    <div class="card-body d-flex">
      <div class="flex-grow-1">
        <slot/>
      </div>
      <div>
        <slot name="actions"/>
      </div>
    </div>
    <div v-if="$slots.footer" class="card-footer">
      <slot name="footer"/>
    </div>
  </div>
</template>

<script>

export default {
}

</script>
