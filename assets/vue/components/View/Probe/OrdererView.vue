<template>
  <div class="d-flex gap-3 flex-column">
    <labeled-value :label="$t('probe.requisition_identifier')">
      {{ probe.requisitionIdentifier }}
    </labeled-value>
    <organization-view :organization="organization"/>
    <practitioner-view :practitioner="practitioner"/>
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import OrganizationView from "../OrganizationView.vue";
import {probeConverter} from "../../../services/domain/converters";
import PractitionerView from "../PractitionerView.vue";

export default {
  components: {PractitionerView, OrganizationView, LabeledValue},
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
  computed: {
    organization: function () {
      return probeConverter.reconstructOrdererOrg(this.probe)
    },
    practitioner: function () {
      return probeConverter.reconstructOrdererPrac(this.probe)
    },
  },
}

</script>
