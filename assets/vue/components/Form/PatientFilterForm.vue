<template>
  <person-component :template="componentTemplate.person" @update="componentEntity.person = $event"/>
  <genders-component :template="componentTemplate.gender" @update="componentEntity.gender = $event"/>
  <hr/>
  <address-component :template="componentTemplate.address" @update="componentEntity.address = $event"/>
</template>

<script>
import {componentForm} from './utils/form'
import AddressComponent, {addressFields} from "./Shared/AddressComponent.vue";
import PersonComponent, {personFields} from "./Shared/PersonComponent.vue";
import GendersComponent, {gendersFields} from "./Shared/GendersComponent.vue";


export default {
  emits: ['update'],
  components: {
    GendersComponent,
    PersonComponent,
    AddressComponent,
  },
  mixins: [componentForm],
  data() {
    return {
      components: {
        person: personFields,
        gender: gendersFields,
        address: addressFields
      }
    }
  }
}
</script>
