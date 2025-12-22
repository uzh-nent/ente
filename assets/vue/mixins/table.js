import {api} from "../services/api";

export const order = {
  data() {
    return {
      orders: [],
    }
  },
  methods: {
    setOrder: function (order, property) {
      this.orders = this.orders.filter(order => order.property !== property)
      if (order) {
        this.orders = [{property, order}].concat(this.orders)
      }
    },
    getOrder: function (property) {
      const orderEntry = this.orders.find(order => order.property === property)
      return orderEntry ? orderEntry.order : null;
    }
  },
}


export const paginatedQueryOrganizations = function(itemsPerPage) {
  return {
    data() {
      return {
        isLoading: false,

        items: [],
        totalItems: 0,

        page: 1,
        itemsPerPage,
      }
    },
    computed: {
      paginatedQuery: function () {
        return Object.assign({page: this.page, itemsPerPage: this.itemsPerPage}, this.query)
      },
    },
    watch: {
      paginatedQuery: {
        handler() {
          this.isLoading = true
          api.getPaginatedOrganisations(this.paginatedQuery).then(response => {
            this.isLoading = false
            this.items = response.items
            this.totalItems = response.totalItems
          })
        },
        deep: true,
        immediate: true
      }
    },
  }
}
