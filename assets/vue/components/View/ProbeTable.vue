<template>
  <div>
    <div class="table-wrapper">
      <table class="table table-striped table-hover border">
        <thead>
        <tr class="bg-light">
          <th colspan="100">
            <div class="d-flex flex-row reset-table-styles gap-2">
              <input type="text" class="form-control mw-15" autofocus
                     :placeholder="$t('_view.search_by_identifier')"
                     v-model="searchIdentifier">
              <input type="text" class="form-control mw-20"
                     :placeholder="$t('_view.search_by_requisition_identifier')"
                     v-model="searchRequisitionIdentifier">
            </div>
          </th>
        </tr>
        <tr>
          <order-table-head :order="orderOfIdentifier" @ordered="setOrder($event, 'identifier')">
            {{ $t('probe.identifier') }}
          </order-table-head>
          <th>{{ $t('probe.service_request') }}</th>
          <th>{{ $t('observation._name') }}</th>
          <th class="w-minimal"></th>
        </tr>
        </thead>
        <tbody>
        <probe-table-row v-for="probe in items" :key="probe['@id']"
                                :probe="probe" :organisms="organisms" />
        <tr v-if="totalItems === 0">
          <td colspan="200">{{ $t('_view.filter_yields_no_entries') }}</td>
        </tr>
        </tbody>
      </table>
      <loading-indicator-overlay v-if="isLoading" />
    </div>
    <pagination :items-per-page="itemsPerPage" :page="page" :total-items="totalItems"
                @paginated="page = $event"/>
  </div>
</template>

<script>
import {order, paginatedQuery} from "./utils/table";
import Pagination from "../Library/Behaviour/Pagination.vue";
import OrderTableHead from "../Library/Behaviour/OrderTableHead.vue";
import OrganizationTableRow from "./OrganizationTableRow.vue";
import {orderFilter, sanitizeSearchFilter} from "../../services/query";
import {localStoragePersisted} from "./utils/state";
import {api} from "../../services/api";
import LoadingIndicatorOverlay from "../Library/View/LoadingIndicatorOverlay.vue";
import ProbeTableRow from "./ProbeTableRow.vue";

export default {
  components: {
    ProbeTableRow,
    LoadingIndicatorOverlay,
    OrganizationTableRow,
    OrderTableHead,
    Pagination,
  },
  mixins: [
    order,
    paginatedQuery(50, api.getPaginatedProbes),
    localStoragePersisted('probe-table', ['filter', 'orders', 'searchIdentifier', 'searchRequisitionIdentifier']),
  ],
  props: {
    organisms: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      filter: {},
      orders: [{property: 'identifier', order: 'asc'}],

      searchIdentifier: "",
      searchRequisitionIdentifier: "",
    }
  },
  computed: {
    query: function () {
      const search = sanitizeSearchFilter({identifier: this.searchIdentifier, requisitionIdentifier: this.searchRequisitionIdentifier})
      const order = orderFilter(this.orders)
      return {...this.filter, ...search, ...order}
    },
    orderOfIdentifier: function () {
      return this.getOrder('identifier')
    },
  }
}

</script>

<style scoped>
.mw-15 {
  max-width: 15em;
}
.mw-20 {
  max-width: 20em;
}
</style>
