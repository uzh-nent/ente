<template>
  <service-request-filter-form :template="componentTemplate.serviceRequest" @update="componentEntity.serviceRequest = $event" />
  <hr/>
  <specimen-meta-filter-form :template="componentTemplate.specimenMeta" @update="componentEntity.specimenMeta = $event" />
  <hr/>
  <service-time-span-form :template="componentTemplate.serviceTimeSpan" @update="componentEntity.serviceTimeSpan = $event" />
</template>

<script>
import {componentForm} from './utils/form'
import ServiceRequestFilterForm, {serviceRequestFilterFields} from "./Probe/ServiceRequestFilterForm.vue";
import ServiceTimeSpanForm, {serviceTimeSpanFields} from "./Probe/ServiceTimeSpanForm.vue";
import SpecimenMetaFilterForm, {specimenMetaFilterFields} from "./Probe/SpecimenMetaFilterForm.vue";

export default {
  emits: ['update'],
  components: {
    SpecimenMetaFilterForm,
    ServiceTimeSpanForm,
    ServiceRequestFilterForm,
  },
  mixins: [componentForm],
  data() {
    return {
      components: {
        serviceRequest: serviceRequestFilterFields,
        specimenMeta: specimenMetaFilterFields,
        serviceTimeSpan: serviceTimeSpanFields,
      }
    }
  }
}
</script>
