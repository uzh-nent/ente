<template>
  <address-component :template="componentTemplate.address" @update="componentEntity.address = $event" />
  <hr/>
  <contact-component :template="componentTemplate.contact" @update="componentEntity.contact = $event" />
</template>

<script>
import {componentForm} from './utils/form'
import AddressComponent, {addressFields} from "./Shared/AddressComponent.vue";
import ContactComponent, {contactFields} from "./Shared/ContactComponent.vue";

export default {
  emits: ['update'],
  components: {
    ContactComponent,
    AddressComponent,
  },
  mixins: [componentForm],
  data() {
    return {
      components: {
        address: addressFields,
        contact: contactFields,
      }
    }
  }
}
</script>
