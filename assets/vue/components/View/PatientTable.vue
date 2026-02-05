<template>
  <div>
    <div class="table-wrapper">
      <table class="table table-striped table-hover border">
        <thead>
        <tr class="bg-light">
          <th colspan="100">
            <div class="d-flex flex-row reset-table-styles gap-2">
              <filter-patient-button :template="this.filter" @filtered="filter = $event"/>
              <date-time-input
                  class="mw-10" id="birthDateFilter" format="date"
                  :placeholder="$t('_view.filter_by_birth_date')"
                  v-model="filterBirthDate" />
              <input type="text" class="form-control mw-30"
                     :placeholder="$t('_view.search_by_ahv_numer')"
                     v-model="searchAhvNumber">
            </div>
          </th>
        </tr>
        <tr>
          <order-table-head :order="orderOfIdentification" @ordered="setOrder($event, 'birthDate')">
            {{ $t('patient._name') }}
          </order-table-head>
          <order-table-head :order="orderOfName" @ordered="setOrder($event, 'familyName')">
            {{ $t('person.name') }}
          </order-table-head>
          <th>{{ $t('address._name') }}</th>
          <th class="w-minimal"></th>
        </tr>
        </thead>
        <tbody>
        <patient-table-row v-for="patient in items" :key="patient['@id']"
                           :patient="patient"/>
        <tr v-if="totalItems === 0">
          <td colspan="200">{{ $t('_view.filter_yields_no_entries') }}</td>
        </tr>
        </tbody>
      </table>
      <loading-indicator-overlay v-if="isLoading"/>
    </div>
    <pagination :items-per-page="itemsPerPage" :page="page" :total-items="totalItems"
                @paginated="page = $event"/>
  </div>
</template>

<script>
import {order, paginatedQuery} from "./utils/table";
import Pagination from "../Library/Behaviour/Pagination.vue";
import OrderTableHead from "../Library/Behaviour/OrderTableHead.vue";
import PatientTableRow from "./PatientTableRow.vue";
import {orderFilter, sanitizeSearchFilter} from "../../services/query";
import {localStoragePersisted} from "./utils/state";
import {api} from "../../services/api";
import LoadingIndicatorOverlay from "../Library/View/LoadingIndicatorOverlay.vue";
import FormField from "../Library/FormLayout/FormField.vue";
import DateTimeInput from "../Library/FormInput/DateTimeInput.vue";
import FilterPatientButton from "../Action/FilterPatientButton.vue";

export default {
  components: {
    FilterPatientButton,
    DateTimeInput, FormField,
    LoadingIndicatorOverlay,
    PatientTableRow,
    OrderTableHead,
    Pagination,
  },
  mixins: [
    order,
    paginatedQuery(50, api.getPaginatedPatients),
    localStoragePersisted('patient-table', ['filter', 'orders', 'filterBirthDate', 'searchAhvNumber'])
  ],
  data() {
    return {
      filter: {},
      orders: [{property: 'name', order: 'asc'}],

      filterBirthDate: "",
      searchAhvNumber: "",
    }
  },
  computed: {
    query: function () {
      const search = sanitizeSearchFilter({birthDate: this.filterBirthDate, ahvNumber: this.searchAhvNumber})
      const order = orderFilter(this.orders)
      return {...this.filter, ...search, ...order}
    },
    orderOfName: function () {
      return this.getOrder('familyName')
    },
    orderOfIdentification: function () {
      return this.getOrder('birthDate')
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

.mw-10 {
  max-width: 10em;
}
</style>
