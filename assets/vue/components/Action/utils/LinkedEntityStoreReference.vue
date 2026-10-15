<template>
  <p class="alert alert-warning">
    <span v-if="otherReferencedOpenProbes.length > 0">
      {{ $t('_form.other_open_probes_reference_entity') }}
      <link-to-probes-list :probes="otherReferencedOpenProbes"/>
    </span>
    <checkbox
        id="storeReference" :label="storeReferenceLabel"
        :modelValue="modelValue" @change="$emit('update:modelValue', !modelValue)"/>
  </p>
</template>

<script>

import LinkToProbesList from "../../View/Probe/LinkToProbesList.vue";
import Checkbox from "../../Library/FormInput/Checkbox.vue";

export default {
  components: {
    Checkbox,
    LinkToProbesList,
  },
  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    otherReferencedOpenProbes: {
      type: Array,
      required: true,
    },
  },
  emits: ['update:modelValue'],
  computed: {
    storeReferenceLabel: function () {
      return this.otherReferencedOpenProbes.length > 0 ?
          this.$t('_form.store_reference_and_update_open_probes') :
          this.$t('_form.store_reference')
    }
  }
}
</script>
