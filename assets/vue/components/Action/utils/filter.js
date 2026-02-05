import {sanitizeFilter} from "../../../services/query";

export const filterAction = {
  emits: ['filtered'],
  data () {
    return {
      filter: null
    }
  },
  props: {
    template: {
      type: Object,
      required: false
    },
  },
  computed: {
    payload: function () {
      const payload = { ...this.template, ...this.filter }
      return sanitizeFilter(payload)
    },
    templateNonTrivial: function () {
      return Object.keys(this.template ?? {}).length > 0
    },
    payloadNonTrivial: function () {
      return Object.keys(this.payload).length > 0
    }
  },
  methods: {
    confirm: async function () {
      this.$emit('filtered', {...this.payload})
    },
    reset: async function () {
      this.$emit('filtered', {})
    },
  }
}

