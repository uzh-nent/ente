<template>
  <div class="mw-elm-reports p-2 bg-light mb-5" v-if="pendingElmReports.length > 0">
    <h3 class="mt-0">{{ $t('_view.pending_elm_reports') }}</h3>
    <pending-elm-report-table class="mb-0" :probes="activeProbes" :users="users" :elm-reports="pendingElmReports"/>
  </div>
  <div class="row">
    <div class="pe-4" :class="'col-lg-' + (this.referenceColumnCount * 3)">
      <h2>{{ $t('probe._laboratory_function.REFERENCE') }}</h2>

      <probe-navigation-view :probes="this.referenceProbes" :column-count="this.referenceColumnCount"
                             :focus="this.referenceColumnCount >= this.primaryColumnCount"/>
    </div>
    <div class="ps-4" :class="'col-lg-' + (this.primaryColumnCount * 3)">
      <h2>{{ $t('probe._laboratory_function.PRIMARY') }}</h2>

      <probe-navigation-view :probes="this.primaryProbes" :column-count="this.primaryColumnCount"
                             :focus="this.primaryColumnCount > this.referenceColumnCount"/>
    </div>
  </div>
</template>

<script>

import ProbeNavigationView from "./components/View/ProbeNavigationView.vue";
import {preloadApi} from "./domain/api";
import PendingElmReportTable from "./components/View/PendingElmReportTable.vue";

export default {
  emits: ['added'],
  components: {
    PendingElmReportTable,
    ProbeNavigationView,
  },
  data() {
    return {
      activeProbes: undefined,
      users: undefined,
      pendingElmReports: undefined
    }
  },
  computed: {
    referenceProbes: function () {
      return this.activeProbes.filter(p => p.laboratoryFunction === 'REFERENCE')
    },
    primaryProbes: function () {
      return this.activeProbes.filter(p => p.laboratoryFunction === 'PRIMARY')
    },
    referenceColumnCount: function () {
      if (this.activeProbes.length === 0) {
        return 1
      }

      const ratio = (this.referenceProbes.length / this.activeProbes.length) * 4
      return Math.max(1, Math.min(3, Math.round(ratio)))
    },
    primaryColumnCount: function () {
      return 4 - this.referenceColumnCount
    },
  },
  beforeMount() {
    const {activeProbes, users, pendingElmReports} = preloadApi.getActiveProbes()
    this.activeProbes = activeProbes
    this.users = users
    this.pendingElmReports = pendingElmReports
  }
}
</script>

<style scoped>
.mw-elm-reports {
  max-width: 40em;
}

</style>
