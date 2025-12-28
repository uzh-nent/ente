<template>
  <div>
    <labeled-value class="mb-3" :label="$t('probe.requisition_identifier')">{{ probe.requisitionIdentifier }}</labeled-value>
    <organization-view :organization="organization"/>
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
    organization: function () {
      return probeConverter.reconstructOrdererOrgOrganization(this.probe)
    },
  },
}

</script>
