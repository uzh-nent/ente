<template>
  <div class="row">
    <div class="pe-4" :class="'col-lg-' + (this.referenceColumnCount * 3)">
      <h2>{{ $t('probe._laboratory_function.REFERENCE') }}</h2>

      <probe-navigation-view :probes="this.referenceProbes" :column-count="this.referenceColumnCount"
                             :focus="this.referenceColumnCount >= this.primaryColumnCount" />
    </div>
    <div class="ps-4" :class="'col-lg-' + (this.primaryColumnCount * 3)">
      <h2>{{ $t('probe._laboratory_function.PRIMARY') }}</h2>

      <probe-navigation-view :probes="this.primaryProbes" :column-count="this.primaryColumnCount"
                             :focus="this.primaryColumnCount > this.referenceColumnCount"/>
    </div>
  </div>
</template>

<script>

import {preloadApi} from './services/api'
import ProbeNavigationView from "./components/View/ProbeNavigationView.vue";

export default {
  emits: ['added'],
  components: {
    ProbeNavigationView,
  },
  data() {
    return {
      activeProbes: undefined,
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
    const {activeProbes} = preloadApi.getActiveProbes()
    this.activeProbes = activeProbes
  }
}
</script>
