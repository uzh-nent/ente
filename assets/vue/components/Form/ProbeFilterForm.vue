<template>
  <service-request-filter-form :template="componentTemplate.serviceRequest" @update="componentEntity.serviceRequest = $event" />
  <hr/>
  <service-time-span-form :template="componentTemplate.serviceTimeSpan" @update="componentEntity.serviceTimeSpan = $event" />
</template>

<script>
import {componentForm} from './utils/form'
import ServiceRequestFilterForm, {serviceRequestFilterFields} from "./Probe/ServiceRequestFilterForm.vue";
import ServiceTimeSpanForm, {serviceTimeSpanFields} from "./Probe/ServiceTimeSpanForm.vue";

export default {
  emits: ['update'],
  components: {
    ServiceTimeSpanForm,
    ServiceRequestFilterForm,
  },
  mixins: [componentForm],
  data() {
    return {
      components: {
        serviceRequest: serviceRequestFilterFields,
        serviceTimeSpan: serviceTimeSpanFields,
      }
    }
  }
}
</script>
