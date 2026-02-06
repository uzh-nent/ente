<template>
  <patient-component :template="componentTemplate.patient" @update="componentEntity.patient = $event"/>
  <hr/>
  <person-component :template="componentTemplate.person" @update="componentEntity.person = $event"/>
  <gender-component :template="componentTemplate.gender" @update="componentEntity.gender = $event"/>
  <hr/>
  <address-component :template="componentTemplate.address" @update="componentEntity.address = $event"/>
</template>

<script>
import {componentForm} from './utils/form'
import AddressComponent, {addressFields} from "./Shared/AddressComponent.vue";
import PatientComponent, {patientFields as patientFieldsD} from "./Shared/PatientComponent.vue";
import GenderComponent, {genderFields} from "./Shared/GenderComponent.vue";
import PersonComponent, {personFields} from "./Shared/PersonComponent.vue";


export default {
  emits: ['update'],
  components: {
    PersonComponent,
    GenderComponent,
    PatientComponent,
    AddressComponent,
  },
  mixins: [componentForm],
  data() {
    return {
      components: {
        patient: patientFieldsD,
        person: personFields,
        gender: genderFields,
        address: addressFields
      }
    }
  }
}

export const patientFields = [...patientFieldsD, ...personFields, ...genderFields, ...addressFields]
</script>
