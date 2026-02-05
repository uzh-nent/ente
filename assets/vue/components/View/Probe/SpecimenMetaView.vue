<template>
  <div>
    <labeled-value :label="$t('probe.specimen_collection_date')">
      {{ formatDate(probe.specimenCollectionDate) }}
    </labeled-value>

    <labeled-value :label="$t('probe.specimen_source')">
      {{formatSpecimenSourceText(probe, $t)}}
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
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import {formatDate, formatSpecimenSourceText} from "../../../services/domain/formatter";

export default {
  methods: {formatSpecimenSourceText, formatDate},
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
