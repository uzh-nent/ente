<template>
  <thing-component :label="$t('animal_keeper._name')"
                   :template="componentTemplate.thing" @update="componentEntity.thing = $event" />
  <hr/>
  <address-component :template="componentTemplate.address" @update="componentEntity.address = $event" />
  <hr/>
  <contact-component :template="componentTemplate.contact" @update="componentEntity.contact = $event" />
</template>

<script>
import {componentForm} from './utils/form'
import AddressComponent, {addressFields} from "./Shared/AddressComponent.vue";
import ContactComponent, {contactFields} from "./Shared/ContactComponent.vue";
import ThingComponent, {thingFields} from "./Shared/ThingComponent.vue";

export default {
  emits: ['update'],
  components: {
    ThingComponent,
    ContactComponent,
    AddressComponent,
  },
  mixins: [componentForm],
  data() {
    return {
      components: {
        thing: thingFields,
        address: addressFields,
        contact: contactFields,
      }
    }
  }
}

export const animalKeeperFields = [...thingFields, ...addressFields, ...contactFields]
</script>
