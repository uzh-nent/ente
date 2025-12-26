<template>
  <labeled-value :label="$t('probe.animal_name')">{{ probe.animalName }}</labeled-value>
  <animal-keeper-view :animal-keeper="probe.animalKeeper" />
</template>

<script>
import AnimalKeeperView from "../AnimalKeeperView.vue";
import LabeledValue from "../../Library/View/LabeledValue.vue";

export default {
  components: {LabeledValue, AnimalKeeperView},
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
}

</script>
