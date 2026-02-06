<template>
  <div class="badge text-bg-primary d-flex align-items-center gap-2" v-if="hasUrlParameters">
    <span>{{ labels.join(", ") }}</span>
    <button class="btn btn-sm btn-danger" @click="clear">
      <i class="fas fa-times"></i>
    </button>
  </div>
</template>

<script>
import LabeledValue from "../../Library/View/LabeledValue.vue";
import {formatDate, formatSpecimenSourceText} from "../../../services/domain/formatter";
import {api} from "../../../services/api";

export default {
  methods: {formatSpecimenSourceText, formatDate},
  components: {LabeledValue},
  data() {
    return {
      labels: [],
    }
  },
  props: {
    urlFilter: {
      type: Object,
      required: true
    },
  },
  computed: {
    hasUrlParameters: function () {
      const url = new URL(window.location.href);
      return url.searchParams.size > 0
    },
    clear: function () {
      const url = new URL(window.location.href);
      url.searchParams.keys().forEach(k => url.searchParams.delete(k))

      window.location.href = url.toString();
    }
  },
  mounted() {
    for (const filterKey in this.urlFilter) {
      if (Object.prototype.hasOwnProperty.call(this.urlFilter, filterKey)) {
        if (filterKey === 'ordererOrg') {
          const key = this.$t("organization._name")
          api.get(this.urlFilter[filterKey]).then(response => {
            this.labels.push(key + ": " + response.name)
          })
        } else if (filterKey === 'ordererPrac') {
          const key = this.$t("practitioner._name")
          api.get(this.urlFilter[filterKey]).then(response => {
            const value = [response.title, response.givenName, response.familyName].filter(v => v).join(" ");
            this.labels.push(key + ": " + value)
          })
        } else if (filterKey === 'patient') {
          const key = this.$t(filterKey + "._name")
          api.get(this.urlFilter[filterKey]).then(response => {
            const value = [response.familyName, response.givenName].filter(v => v).join(" ");
            this.labels.push(key + ": " + value)
          })
        } else if (filterKey === 'animalKeeper') {
          const key = this.$t("animal_keeper._name")
          api.get(this.urlFilter[filterKey]).then(response => {
            this.labels.push(key + ": " + response.name)
          })
        } else {
          this.labels.push(filterKey + ": " + this.urlFilter[filterKey])
        }
      }
    }
  }
}

</script>
