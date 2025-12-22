<template>
  <div>
    <div class="table-wrapper">
      <table class="table table-striped table-hover border">
        <thead>
        <tr class="bg-light">
          <th colspan="100">
            <div class="d-flex flex-row reset-table-styles gap-2">
              <input type="text" class="form-control mw-5"
                     :placeholder="$t('address.postal_code')"
                     v-model="searchPostalCode">
              <input type="text" class="form-control mw-30"
                     :placeholder="$t('_view.search_by_name')"
                     v-model="searchName">
            </div>
          </th>
        </tr>
        <tr>
          <order-table-head :order="orderOfName" @ordered="setOrder($event, 'name')">
            {{ $t('organization.name') }}
          </order-table-head>
          <th>{{ $t('address.address_lines') }}</th>
          <th>{{ $t('address.city') }}</th>
          <th>{{ $t('contact.contact') }}</th>
          <th class="w-minimal"></th>
        </tr>
        </thead>
        <tbody>
        <organization-table-row v-for="organization in items" :key="organization['@id']"
                                :organization="organization"/>
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
import {order, paginatedQuery} from "../../mixins/table";
import Pagination from "../Library/Behaviour/Pagination.vue";
import OrderTableHead from "../Library/Behaviour/OrderTableHead.vue";
import OrganizationTableRow from "./OrganizationTableRow.vue";
import {createQuery} from "../../services/query";
import {localStoragePersisted} from "../../mixins/state";
import {api} from "../../services/api";
import LoadingIndicatorOverlay from "../Library/View/LoadingIndicatorOverlay.vue";

export default {
  components: {
    LoadingIndicatorOverlay,
    OrganizationTableRow,
    OrderTableHead,
    Pagination,
  },
  mixins: [
    order,
    paginatedQuery(50, api.getPaginatedOrganisations),
    localStoragePersisted('organization-table', ['filter', 'orders', 'searchName', 'searchPostalCode'])
  ],
  data() {
    return {
      filter: {},
      orders: [{property: 'name', order: 'asc'}],

      searchName: "",
      searchPostalCode: "",
    }
  },
  computed: {
    query: function () {
      const filter = {...this.filter, name: this.searchName, postalCode: this.searchPostalCode}
      return createQuery(
          {},
          [],
          ['name', 'postalCode'],
          [],
          filter,
          this.orders
      )
    },
    orderOfName: function () {
      return this.getOrder('name')
    },
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

.mw-30 {
  max-width: 30em;
}

.mw-5 {
  max-width: 5em;
}
</style>
