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


export const paginatedQuery = function (itemsPerPage, loadItems) {
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
          this.reload()
        },
        deep: true,
        immediate: true
      }
    },
    methods: {
      reload: function () {
        this.isLoading = true
        loadItems(this.paginatedQuery).then(response => {
          this.isLoading = false
          this.items = response.items
          this.totalItems = response.totalItems
        })
      }
    }
  }
}
