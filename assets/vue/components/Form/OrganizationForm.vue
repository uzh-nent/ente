<template>
  <business-identifier-component
      :template="componentTemplate.businessIdentifier" @update="componentEntity.businessIdentifier = $event"/>
  <thing-component :label="$t('organization._name')"
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
import BusinessIdentifierComponent, {businessIdentifierFields} from "./Shared/BusinessIdentifierComponent.vue";

export default {
  emits: ['update'],
  components: {
    BusinessIdentifierComponent,
    ThingComponent,
    ContactComponent,
    AddressComponent,
  },
  mixins: [componentForm],
  data() {
    return {
      components: {
        businessIdentifier: businessIdentifierFields,
        thing: thingFields,
        address: addressFields,
        contact: contactFields,
      }
    }
  }
}
export const organizationFields = [...businessIdentifierFields, ...thingFields, ...addressFields, ...contactFields]
</script>
