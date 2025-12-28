<template>
  <div>
    <labeled-value class="mb-3" :label="$t('probe.requisition_identifier')">{{ probe.requisitionIdentifier }}</labeled-value>
    <organization-view :organization="orderer"/>
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import OrganizationView from "../OrganizationView.vue";
import {probeConverter} from "../../../services/domain/converters";

export default {
  components: {OrganizationView, LabeledValue},
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
  computed: {
    orderer: function () {
      return probeConverter.reconstructOrdererOrganization(this.probe)
    },
  },
}

</script>
