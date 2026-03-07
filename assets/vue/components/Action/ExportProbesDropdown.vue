<template>
  <div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
      <i class="fas fa-download"/>
    </button>
    <ul class="dropdown-menu">
      <li>
        <button class="dropdown-item" @click="exportAllProbes" :disabled="downloading">
          {{ $t("_action.export_probes.export_all") }}
        </button>
      </li>
      <li>
        <button class="dropdown-item" @click="exportFilteredProbes" :disabled="downloading">
          {{ $t("_action.export_probes.export_filtered") }}
        </button>
      </li>
      <li>
        <button class="dropdown-item" @click="exportOldProbes('PRIMARY')">
          {{ $t("_action.export_probes.export_pre_2025_p") }}
        </button>
      </li>
      <li>
        <button class="dropdown-item" @click="exportOldProbes('REFERENCE')">
          {{ $t("_action.export_probes.export_pre_2025_n") }}
        </button>
      </li>
    </ul>
  </div>
</template>

<script>

import {api, router} from '../../services/api'
import {displaySuccess} from '../../services/notifiers'
import LoopingRhombusSpinner from '../Library/View/Base/LoopingRhombusSpinner.vue'
import ButtonConfirmModal from '../Library/Behaviour/Modal/ButtonConfirmModal.vue'
import AnimalKeeperForm from "../Form/AnimalKeeperForm.vue";
import moment from "moment/moment";

export default {
  props: {
    filter: {
      type: Object,
      default: {},
    }
  },
  data() {
    return {
      downloading: false,
    }
  },
  methods: {
    exportFilteredProbes: function () {
      return this.exportProbes(this.filter)
    },
    exportAllProbes: function () {
      return this.exportProbes({})
    },
    exportOldProbes: function (laboratoryFunction) {
      window.location.href = router.pre2025ProbesExport(laboratoryFunction)
    },
    exportProbes: async function (filter) {
      this.downloading = true;

      const response = await api.getProbesExcel(filter)
      const blob = new Blob([response],  {
        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      })

      const downloadUrl = window.URL.createObjectURL(blob)
      const link = document.createElement('a')
      link.href = downloadUrl
      link.download = 'worksheet.xlsx'
      link.click()
      window.URL.revokeObjectURL(downloadUrl)

      this.downloading = false;
    }
  },
}
</script>
