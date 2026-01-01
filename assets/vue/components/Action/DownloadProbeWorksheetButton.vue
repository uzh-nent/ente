<template>
  <a class="btn" :class="{'btn-primary': !hasObservations, 'btn-secondary': !hasObservations}"
     :href="link" download>
    <i class="fas fa-download" />
    {{ $t('_action.download_probe_worksheet.download') }}
  </a>
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
    probe: {
      type: Object,
      required: true
    },
    hasObservations: {
      type: Boolean,
      required: false
    }
  },
  computed: {
    link: function () {
      return router.linkProbeWorksheetPdf(this.probe)
    }
  },
}
</script>
