<template>
    <span class="badge" :class="{
          'bg-warning': !observation.interpretation,
          'bg-danger': observation.interpretation === 'POS' ,
          'bg-success': observation.interpretation === 'NEG'
        }">
      {{ $t(`observation._interpretation.${observation.interpretation ?? 'NONE'}`) }}
    </span>
</template>

<script>

export default {
  props: {
    observation: {
      type: Object,
      required: true
    },
  }
}

</script>
