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
  </div>
</template>

<script>

export default {}

</script>
