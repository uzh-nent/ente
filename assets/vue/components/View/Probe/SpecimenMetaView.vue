<template>
  <labeled-value :label="$t('probe.specimen_date')">
    {{ formatDate(probe.specimenDate) }}
  </labeled-value>

  <labeled-value :label="$t('probe.specimen_source')">
    <template v-if="!probe.specimenSource">
      {{ probe.specimenSourceText }}
    </template>
    <template v-else>
      {{ $t(`probe._specimen_source.${probe.specimenSource}`) }}
      <template v-if="probe.specimenSource === 'FOOD'">
        <template v-if="probe.specimenFoodType">
          {{ $t(`probe._specimen_food_type.${probe.specimenFoodType}`) }}
        </template>
        <template v-else>
          {{ probe.specimenTypeText }}
        </template>
      </template>
      <template v-else-if="probe.specimenSource === 'ANIMAL'">
        <template v-if="probe.specimenAnimalType">
          {{ $t(`probe._specimen_animal_type.${probe.specimenAnimalType}`) }}
        </template>
        <template v-else>
          {{ probe.specimenTypeText }}
        </template>
      </template>
      <template v-else-if="probe.specimenSource !== 'HUMAN'">
        {{ probe.specimenTypeText }}
      </template>
    </template>
  </labeled-value>

  <labeled-value v-if="probe.specimenSource === 'HUMAN'" :label="$t('specimen._name')">
    <template v-if="probe.specimen">
      {{ specimen?.displayName }}
    </template>
    <template v-else-if="probe.specimenText">
      {{ probe.specimenText }}
    </template>
    <template v-if="probe.specimenIsolate">
      <span class="badge bg-secondary ms-2">{{ $t('probe.specimen_isolate') }}</span>
    </template>
  </labeled-value>
  <labeled-value v-if="probe.specimenSource !== 'HUMAN'" :label="$t('probe.specimen_text')">
    {{ probe.specimenText }}
  </labeled-value>

  <labeled-value v-if="probe.specimenSource !== 'HUMAN'  && probe.specimenSource !== 'ANIMAL'"
                 :label="$t('probe.specimen_location')">
    {{ probe.specimenLocation }}
  </labeled-value>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import {formatDate} from "../../../services/formatter";

export default {
  methods: {formatDate},
  components: {LabeledValue},
  props: {
    probe: {
      type: Object,
      required: true
    },
    specimens: {
      type: Array,
      required: true
    },
  },
  computed: {
    specimen: function () {
      if (!this.probe.specimen) {
        return null;
      }

      return this.specimens.find(s => s['@id'] === this.probe.specimen)
    }
  }
}

</script>
