<template>
  <labeled-value class="mb-3" :label="$t('probe.animal_name')">{{ probe.animalName }}</labeled-value>
  <animal-keeper-view :animal-keeper="owner" />
</template>

<script>
import AnimalKeeperView from "../AnimalKeeperView.vue";
import LabeledValue from "../../Library/View/LabeledValue.vue";
import {probeConverter} from "../../../services/domain";

export default {
  components: {LabeledValue, AnimalKeeperView},
  props: {
    probe: {
      type: Object,
      required: true
    },
  },
  computed: {
    owner: function () {
      return probeConverter.reconstructAnimalKeeper(this.probe)
    },
  },
}

</script>
