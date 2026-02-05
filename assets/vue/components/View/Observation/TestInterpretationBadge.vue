<template>
    <span class="badge" :class="{
          'bg-warning': !observation.interpretation,
          'bg-success': observation.interpretation === 'POS' ,
          'bg-danger': observation.interpretation === 'NEG'
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
