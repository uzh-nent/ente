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
        {{ probe.analysisTypes
              .map(t => t === 'IDENTIFICATION' ? $t(`probe._pathogen.${probe.pathogen}`) : $t(`probe._analysis_type_short.${t}`))
              .join(', ')
        }}
      </template>
      <template v-else>
        {{ probe.pathogenName }}
      </template>
    </labeled-value>

    <labeled-value :label="$t('probe.method_types')">
      {{ probe.methodTypes.map(t => $t(`probe._method_type.${t}`)).join(', ') }}
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
