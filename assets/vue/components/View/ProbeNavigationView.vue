<template>
  <div>
    <div v-if="probes.length === 0" class="row">
      <div :class="'col-lg-' + (12 / this.columnCount)">
        <div class="alert alert-info">
          {{ $t('_view.probe_navigation.no_probes') }}
        </div>
      </div>
    </div>

    <div v-else>
      <div class="input-group mb-3">
        <span v-if="prefix" class="input-group-text" id="prefix">{{ prefix }}</span>
        <input id="filterProbes" type="text" class="form-control mw-5"
               aria-describedby="prefix"
               v-model="filterProbes">
      </div>

      <div v-if="filteredProbes.length === 0" class="row">
        <div :class="'col-lg-' + (12 / this.columnCount)">
          <div class="alert alert-info">
            {{ $t('_view.probe_navigation.no_probes') }}
          </div>
        </div>
      </div>

      <div class="row">
        <div v-for="probe in filteredProbes" :key="probe['@id']"
             class="mb-4"
             :class="'col-lg-' + (12 / this.columnCount)">
          <a class="card clickable hover-bg-light no-underline" :href="probeHref(probe)" tabindex="0">
            <div class="card-body">
              <b>{{ probe.identifier }}</b> <br/>

              {{ formatService(probe) }}
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import {formatProbeService} from "../../services/domain/formatter";
import {router} from "../../services/api";

const PREFIX_LENGTH = 4

export default {
  emits: ['navigated'],
  props: {
    probes: {
      type: Array,
      required: true
    },
    columnCount: {
      type: Number,
      required: true
    },
    focus: {
      type: Boolean,
      required: false
    }
  },
  data() {
    return {
      filterProbes: null
    }
  },
  computed: {
    prefix: function () {
      if (!this.probes.length) {
        return null
      }

      const first = this.probes[0].identifier.substring(0, PREFIX_LENGTH)
      const last = this.probes[this.probes.length-1].identifier.substring(0, PREFIX_LENGTH)
      if (first === last) {
        return first
      }

      return first.substring(0, 1) + "2X-"
    },
    filteredProbes: function () {
      if (!this.filterProbes) {
        return this.probes
      }

      return this.probes.filter(p => p.identifier.substring(PREFIX_LENGTH).includes(this.filterProbes))
    },
  },
  methods: {
    formatService: function (probe) {
      return formatProbeService(probe, this.$t)
    },
    probeHref: function (probe) {
      return router.probeActiveView(probe)
    }
  },
  watch: {
    filteredProbes: {
      handler: function (filteredProbes) {
        if (filteredProbes.length === 1 && this.filterProbes?.length > 0) {
          window.location = this.probeHref(filteredProbes[0])
        }
      }
    }
  },
  mounted() {
    if (this.focus) {
      this.$nextTick(() => {
        document.getElementById('filterProbes')?.focus()
      })
    }
  }
}

</script>

<style scoped>
.no-underline {
  text-decoration: none;
}
</style>
