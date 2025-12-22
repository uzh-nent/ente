<template>
  <nav class="clearfix" v-if="totalItems > itemsPerPage">
    <ul class="pagination">
      <li class="page-item" v-if="page > 2">
        <a class="page-link" href="#" aria-label="Previous" @click.prevent="paginate(1)">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
      <li class="page-item" v-if="page > 1">
        <a class="page-link" href="#" aria-label="Previous" @click.prevent="paginate(page - 1)">
          <span aria-hidden="true">&lt;</span>
        </a>
      </li>
      <li class="page-item" v-for="predecessorPage in predecessorPages" :key="predecessorPage">
        <a class="page-link" href="#" @click.prevent="paginate(predecessorPage)">{{ predecessorPage }}</a>
      </li>
      <li class="page-item active">
        <a class="page-link" href="#">{{ page }}</a>
      </li>
      <li class="page-item" v-for="successorPage in successorPages" :key="successorPage">
        <a class="page-link" href="#" @click.prevent="paginate(successorPage)">{{ successorPage }}</a>
      </li>
      <li class="page-item" v-if="page < maxPage">
        <a class="page-link" href="#" aria-label="Next" @click.prevent="paginate(page + 1)">
          <span aria-hidden="true">&gt;</span>
        </a>
      </li>
      <li class="page-item" v-if="page < maxPage - 1">
        <a class="page-link" href="#" aria-label="Next" @click.prevent="paginate(maxPage)">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>

const numberOfPagesShown = 4

export default {
  emits: ['paginated'],
  props: {
    page: {
      type: Number,
      required: true
    },
    itemsPerPage: {
      type: Number,
      required: true
    },
    totalItems: {
      type: Number,
      default: 0
    }
  },
  watch: {
    maxPage: function () {
      if (this.maxPage < this.page) {
        this.$emit('paginated', 1)
      }
    }
  },
  methods: {
    paginate: function (page) {
      this.$emit('paginated', page)
    }
  },
  computed: {
    minPageShown: function () {
      return Math.max(1, this.page - numberOfPagesShown)
    },
    predecessorPages: function () {
      const pageCount = this.page - this.minPageShown
      if (pageCount > 0) {
        return Array.from(Array(pageCount).keys(), (x, i) => i + this.minPageShown)
      }
      return []
    },
    maxPage: function () {
      return Math.ceil(this.totalItems / this.itemsPerPage)
    },
    maxPageShown: function () {
      return Math.min(this.maxPage, this.page + numberOfPagesShown)
    },
    successorPages: function () {
      const pageCount = this.maxPageShown - this.page
      if (pageCount > 0) {
        return Array.from(Array(pageCount).keys(), (x, i) => i + this.page + 1)
      }
      return []
    }
  }
}

</script>

<style scoped>
.pagination {
  margin: 10px 0;
  float: right;
}

</style>
