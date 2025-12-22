import debounce from 'lodash.debounce'

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
    watch: {
      query: {
        handler: debounce(function (newQuery) {
          this.load(this.page, newQuery)
        }, 200, {'leading': true}),
        deep: true,
      },
      page: {
        handler: function (newVal) {
          this.load(newVal, this.query)
        },
        deep: true,
        immediate: true
      }
    },
    methods: {
      reload: function () {
        this.load(this.page, this.query)
      },
      load: function (page, query) {
        const paginatedQuery = Object.assign({page, itemsPerPage}, query)

        this.isLoading = true
        loadItems(paginatedQuery).then(response => {
          this.isLoading = false
          this.items = response.items
          this.totalItems = response.totalItems
        })
      }
    }
  }
}
