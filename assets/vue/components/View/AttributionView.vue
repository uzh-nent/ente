<template>
  <span class="text-nowrap">
    <template v-if="hasChanges">
      {{ formatDateTime(entity.lastChangedAt) }} / {{ lastChangedBy?.abbreviation }}
    </template>
    <template v-else>
      {{ formatDateTime(entity.createdAt) }} / {{ createdBy?.abbreviation }}
    </template>
  </span>
</template>

<script>
import {formatAddressCity, formatDateTime} from "../../services/domain/formatter";
import EditAnimalKeeperButton from "../Action/EditAnimalKeeperButton.vue";

export default {
  methods: {formatDateTime},
  components: {EditAnimalKeeperButton},
  props: {
    entity: {
      type: Object,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
  },
  computed: {
    hasChanges: function () {
      return this.entity.createdAt !== this.entity.lastChangedAt
    },
    createdBy: function () {
      return this.users.find(u => u['@id'] === this.entity.createdBy)
    },
    lastChangedBy: function () {
      return this.users.find(u => u['@id'] === this.entity.lastChangedBy)
    }
  }
}

</script>

