<template>
  <div class="text-bg-light p-2 d-flex">
    <div class="flex-grow-1">
      <slot />
    </div>
    <div>
    <slot name="actions"/>
    </div>
  </div>
</template>

<script>

export default {
}

</script>
