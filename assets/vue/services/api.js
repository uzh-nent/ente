import axios from 'axios'

const api = {
  _getUrl: function (url, query = null) {
    const fullUrl = new URL(window.location.origin)

    if (query) {
      Object.keys(query).forEach(key => {
        if (Array.isArray(query[key])) {
          query[key].forEach(value => fullUrl.searchParams.append(key+"[]", value))
        } else {
          fullUrl.searchParams.append(key, query[key])
        }
      })
    }

    return fullUrl.toString()
  },
  _get: function (url) {
    return new Promise(
      (resolve) => {
        axios.get(this._getApiUrl(url), {headers: {"Accept": "application/ld+json"}})
          .then(response => {
            resolve(response.data)
          })
      }
    )
  },
  _getHydraCollection: function (url) {
    return new Promise(
        (resolve) => {
          this._get(url)
              .then(data => {
                resolve(data['member'])
              })
        }
    )
  },
  _getPaginatedHydraCollection: function (url) {
    return new Promise(
        (resolve) => {
          this._get(url)
              .then(data => {
                const payload = {
                  items: data['hydra:member'],
                  totalItems: data['hydra:totalItems']
                }
                resolve(payload)
              })
        }
    )
  },
  _getCSV: function (url) {
    return new Promise(
        (resolve) => {
          axios.get(this._getApiUrl(url), {headers: {"Accept": "text/csv"}})
              .then(response => {
                resolve(response.data)
              })
        }
    )
  },
  setupErrorNotifications: function (translator) {
    axios.interceptors.response.use(
        response => {
          return response
        },
        error => {
          console.log(error)
          // hide aborted errors (happens when navigating rapidly in firefox)
          /* eslint-disable-next-line eqeqeq */
          if (error.code == 'ECONNABORTED') {
            return Promise.reject(error)
          }

          let errorText = error
          if (error.response) {
            const response = error.response
            if (response.data['hydra:title'] && response.data['hydra:description']) {
              errorText = response.data['hydra:title'] + ': ' + response.data['hydra:description']
            } else {
              errorText = response.status
              if (response.data && response.data.detail) {
                errorText += ': ' + response.data.detail
              } else if (response.statusText) {
                errorText += ': ' + response.statusText
              }
            }
          }

          const errorMessage = translator('_api.request_failed') + ' (' + errorText + ')'
          alert(errorMessage)

          return Promise.reject(error)
        }
    )
  },
  getPaginatedOrganisations: function () {
    const fullUrl = this._getUrl('/api/organizations', query)
    return this._getPaginatedHydraCollection(fullUrl)
  },
}

export { api }
