<template>
  <div>
    <div class="table-wrapper">
      <table class="table table-striped table-hover border">
        <thead>
        <tr>
          <th>{{ $t('probe.service_request') }}</th>
          <th>{{ $t('address.city') }}</th>
          <th>{{ $t('contact.contact') }}</th>
          <th class="w-minimal"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="observation in observations" :key="observation['@id']">
          <td>{{$t(`probe._analysis_type.${observation.analysisType}`)}}</td>
          <td>{{ formatDate(observation.analysisStopAt)}}</td>
          <td>
            <template v-if="observation.analysisType === 'IDENTIFICATION'">
              <span class="badge bg-success" v-if="observation.interpretation ==='POS'">
                {{ $t(`message.successful`) }}
              </span>
              <span class="badge bg-success" v-if="observation.interpretation ==='NEG'">
                {{ $t(`message.failed`) }}
              </span>
            </template>
          </td>
          <td>

          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>

import {formatDate} from "../../services/domain/formatter";

export default {
  methods: {formatDate},
  props: {
    observations: {
      type: Array,
      required: true
    }
  }
}

</script>

<style scoped>
.table-wrapper {
  position: relative;
}

.reset-table-styles {
  text-align: left;
  font-weight: normal;
}
</style>
