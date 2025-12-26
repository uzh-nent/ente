<template>
  <labeled-value class="mb-3" :label="$t('probe.orderer_identifier')">{{ probe.ordererIdentifier }}</labeled-value>
  <organization-view :organization="orderer" />
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import OrganizationView from "../OrganizationView.vue";
import {probeConverter} from "../../../services/domain";

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
