import moment from "moment/moment";

export const periodExport = {
  data () {
    return {
      period: null,
      downloading: false
    }
  },
  props: {
    template: {
      type: Object,
      required: false
    },
  },
  computed: {
    canConfirm: function () {
      return !!this.period && !this.downloading
    },
    extendedTemplate: function () {
      const from = moment().startOf('month').subtract(1, 'month')
      const to = moment()
      return {
        ...this.template,
        'period[after]': from.format('YYYY-MM-DD'),
        'period[before]': to.format('YYYY-MM-DD'),
      }
    },
    payload: function () {
      return {...this.extendedTemplate, ...this.period}
    },
  },
  methods: {
    focusPeriod: function () {
      document.getElementById('period')?.focus()
    }
  }
}
