<template>
  <div>
    <labeled-value :label="$t('probe.laboratory_function')">
      {{ $t(`probe._laboratory_function.${probe.laboratoryFunction}`) }}
    </labeled-value>

    <labeled-value v-if="probe.laboratoryFunction === 'PRIMARY'" :label="$t('service.ecoli_identification')">
      {{ probe.analysisTypes.map(t => $t(`probe._analysis_type_short.${t}`)).join(', ') }}
    </labeled-value>

    <labeled-value v-if="probe.laboratoryFunction === 'REFERENCE'" :label="$t('service.identification_typing')">
      <template v-if="probe.pathogen">
        {{ $t(`probe._pathogen.${probe.pathogen}`) }}
      </template>
      <template v-else>
        {{ probe.pathogenText }}
      </template>
    </labeled-value>
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";

export default {
  components: {LabeledValue},
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
}

</script>
