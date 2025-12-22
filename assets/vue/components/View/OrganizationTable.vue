<template>
  <div class="table-wrapper">
    <table class="table table-striped table-hover table-bordered-outline">
      <thead>
      <tr class="bg-light">
        <th colspan="100">
          <div class="d-flex flex-row reset-table-styles">
            <filter-organizations-button
                :template="filter" @update="filter = $event"/>

            <input type="text" class="form-control mw-5 ms-2"
                   :placeholder="$t('address.postal_code')"
                   v-model="filterPostalCode">
            <input type="text" class="form-control mw-30 ms-2"
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
      <loading-indicator-table-body v-if="!items" />
      <organization-table-row v-for="organization in items" :key="organization.id"
                              :organization="organization"/>
      <tr v-if="totalItems === 0">
        <td colspan="200">{{ $t('_view.filter_yields_no_entries') }}</td>
      </tr>
      </tbody>
    </table>
  </div>
  <pagination :items-per-page="itemsPerPage" :page="page" :total-items="totalItems"
              @paginated="page = $event"/>
</template>

<script>
import {order, paginatedQuery} from "../../mixins/table";
import Pagination from "../Library/Behaviour/Pagination.vue";
import OrderTableHead from "../Library/Behaviour/OrderTableHead.vue";
import FilterOrganizationsButton from "../Action/FilterOrganizationsButton.vue";
import OrganizationTableRow from "./OrganizationTableRow.vue";
import {createQuery} from "../../services/query";
import {localStoragePersisted} from "../../mixins/state";
import LoadingIndicatorTableBody from "../Library/View/LoadingIndicatorTableBody.vue";
import {api} from "../../services/api";

const queryConfiguration = {
  exactProps: ['type', 'structure', 'faculty', 'livecycleStatus'],
  searchProps: ['name', 'fullName', 'abbreviation', 'sapObjectId'],
  dateTimeProps: ["createdAt", "updatedAt"],
}

export default {
  components: {
    LoadingIndicatorTableBody,
    OrganizationTableRow,
    FilterOrganizationsButton,
    OrderTableHead,
    Pagination,
  },
  mixins: [
    order,
    paginatedQuery(50, api.getPaginatedOrganisations),
    localStoragePersisted('organization-table', ['filter', 'orders', 'searchName', 'filterPostalCode'])
  ],
  data() {
    return {
      filter: {},
      orders: [{property: 'name', order: 'asc'}],

      searchName: "",
      filterPostalCode: "",
    }
  },
  computed: {
    query: function () {
      const filter = {...this.filter, name: this.searchName, postalCode: this.filterPostalCode}
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
