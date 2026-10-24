<template>
  <table class="table table-striped table-hover border">
    <tbody>
    <pending-elm-report-table-row
        v-for="elmReport in elmReports" :key="elmReport['@id']"
        :elm-report="elmReport" :probe="findProbe(elmReport.probe)" :users="users"
    />
    </tbody>
  </table>
</template>

<script>

import ElmReportTableRow from "./ElmReportTableRow.vue";
import PendingElmReportTableRow from "./PendingElmReportTableRow.vue";
import {api} from "../../domain/api";

export default {
  components: {PendingElmReportTableRow, ElmReportTableRow},
  props: {
    elmReports: {
      type: Array,
      required: true
    },
    users: {
      type: Array,
      required: true
    },
    probes: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      loadedProbes: []
    }
  },
  computed: {
    missingProbes: function () {
      const allProbes = new Set(this.elmReports.map(r => r.probe))
      const knownProbes = new Set(this.probes.map(p => p['@id']))

      return Array.from(allProbes).filter(probe => !knownProbes.has(probe))
    }
  },
  methods: {
    findProbe: function (id) {
      const probe = this.probes.find(p => p['@id'] === id)
      if (probe) {
        return probe
      }

      return this.loadedProbes.find(p => p['@id'] === id)
    }
  },
  mounted() {
    this.missingProbes.map(async p => {
      const probe = await api.get(p)
      this.loadedProbes.push(probe)
    })
  }
}

</script>
