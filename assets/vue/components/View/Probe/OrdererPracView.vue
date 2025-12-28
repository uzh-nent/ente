<template>
  <div>
    <labeled-value class="mb-3" :label="$t('probe.requisition_identifier')">{{ probe.requisitionIdentifier }}</labeled-value>
    <practitioner-view :practitioner="practitioner"/>
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import PractitionerView from "../PractitionerView.vue";
import {probeConverter} from "../../../services/domain/converters";

export default {
  components: {PractitionerView, LabeledValue},
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
  computed: {
    practitioner: function () {
      return probeConverter.reconstructOrdererPracPractitioner(this.probe)
    },
  },
}

</script>
