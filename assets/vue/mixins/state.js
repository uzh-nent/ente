import debounce from "lodash.debounce";

export const localStoragePersisted = function(componentIdentifier, properties) {
  return {
    watch: {
      persistedState: {
        handler: debounce(function (newVal) {
          localStorage.setItem(this.persistedStateKey, JSON.stringify(newVal))
        }, 200, {'leading': true}),
        deep: true
      },
    },
    computed: {
      persistedStateKey: function () {
        return $('#shortname').text() + componentIdentifier;
      },
      persistedState: function () {
        const state = {}
        properties.forEach(property => {
          state[property] = this[property]
        })
        return state
      },
    },
    mounted() {
      // recover old query state
      const state = localStorage.getItem(this.persistedStateKey);
      if (state) {
        const payload = JSON.parse(state)
        properties.forEach(property => {
          if (state.hasOwnProperty(property)) {
            this[property] = state[property]
          }
        })
      }
    }
  }
}
