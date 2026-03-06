<template>
  <gln-component :template="componentTemplate.gln" @update="componentEntity.gln = $event"/>
  <div class="row">
    <div class="col-md-3">
      <title-component :template="componentTemplate.title" @update="componentEntity.title = $event"/>
    </div>
    <div class="col-md-9">
      <person-component :template="componentTemplate.person" @update="componentEntity.person = $event"/>
    </div>
  </div>

  <hr/>
  <address-component :template="componentTemplate.address" @update="componentEntity.address = $event"/>
  <hr/>
  <contact-component :template="componentTemplate.contact" @update="componentEntity.contact = $event"/>
</template>

<script>
import {componentForm} from './utils/form'
import AddressComponent, {addressFields} from "./Shared/AddressComponent.vue";
import PersonComponent, {personFields} from "./Shared/PersonComponent.vue";
import ContactComponent, {contactFields} from "./Shared/ContactComponent.vue";
import TitleComponent, {titleFields} from "./Shared/TitleComponent.vue";
import GlnComponent, {glnFields} from "./Shared/GlnComponent.vue";


export default {
  emits: ['update'],
  components: {
    GlnComponent,
    TitleComponent,
    ContactComponent,
    PersonComponent,
    AddressComponent,
  },
  mixins: [componentForm],
  data() {
    return {
      components: {
        gln: glnFields,
        title: titleFields,
        person: personFields,
        address: addressFields,
        contact: contactFields,
      }
    }
  }
}

export const practitionerFields = [...glnFields, ...titleFields, ...personFields, ...addressFields, ...contactFields]
</script>
