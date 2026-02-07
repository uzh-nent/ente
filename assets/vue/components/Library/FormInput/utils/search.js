import debounce from "lodash.debounce";

const SEARCH_CUTOFF = 50

export const searchableChoices = {
  emits: ['update'],
  data() {
    return {
      searchChoice: null,
      filteredChoices: null,
      filteredChoicesTerm: null
    }
  },
  computed: {
    searchEnabled: function () {
      return this.choices.length > SEARCH_CUTOFF
    },
    shownChoices: function () {
      let source = this.searchEnabled ? this.filteredChoices : this.choices
      source = !source && this.modelValue ? this.choices.filter(c => c.value === this.modelValue) : source
      return source?.slice(0, SEARCH_CUTOFF) ?? [];
    },
    itemHits: function () {
      if (!this.searchChoice) {
        return null
      }

      let hits = this.shownChoices.length;
      if (this.shownChoices.length < this.filteredChoices?.length) {
        hits += `+`
      }

      return this.$t('_action.hits', {hits: hits})
    }
  },
  methods: {
    filterChoices: function (searchChoice) {
      if (!searchChoice) {
        this.filteredChoices = null
        this.filteredChoicesTerm = null
        return
      }

      const extendsPreviousSearch = this.filteredChoicesTerm && this.filteredChoices && searchChoice.includes(this.filteredChoicesTerm);
      const base = [...(extendsPreviousSearch ? this.filteredChoices : this.choices)]
      const keywords = searchChoice.split(" ").map(kw => kw.toLowerCase())
      this.filteredChoices = base.filter(c => {
        const match = c.label.toLowerCase()
        return keywords.every(kw => match.includes(kw))
      })
      this.filteredChoicesTerm = searchChoice
    }
  },
  watch: {
    searchChoice: {
      handler: debounce(function (searchChoice) {
        this.filterChoices(searchChoice)
      }, 200, {'leading': true}),
    }
  }
}
