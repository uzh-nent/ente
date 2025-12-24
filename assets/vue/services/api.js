import axios from 'axios'
import {displayError} from "./notifiers";


const iriToId = function (iri) {
  return iri.substr(iri.lastIndexOf('/') + 1)
}

const restClient = {
  setupErrorNotifications: function () {
    axios.interceptors.response.use(
      response => {
        return response
      },
      error => {
        /* eslint-disable-next-line eqeqeq */
        if (error == 'AxiosError: Request aborted') {
          // hide aborted errors (happens when navigating rapidly in firefox)
          return
        }

        console.log(error)

        let errorText = error
        if (error.response) {
          const response = error.response
          if (response.data.title && response.data.description) {
            errorText = response.data.title + ': ' + response.data.description
          } else {
            errorText = response.status
            if (response.data && response.data.detail) {
              errorText += ': ' + response.data.detail
            } else if (response.statusText) {
              errorText += ': ' + response.statusText
            }
          }
        }

        displayError('Failed: ' + errorText)

        return Promise.reject(error)
      }
    )
  },
  _writeAllProperties: function (instance, patch, responseData) {
    // null values are not delivered in response, hence take them from patch
    for (const prop in patch) {
      if (Object.prototype.hasOwnProperty.call(patch, prop) && patch[prop] === null) {
        instance[prop] = null
      }
    }

    for (const prop in responseData) {
      if (Object.prototype.hasOwnProperty.call(responseData, prop)) {
        instance[prop] = responseData[prop]
      }
    }
  },
  _getFullUrl: function (url, query) {
    const fullUrl = new URL(url, window.location.origin)
    Object.keys(query).forEach(key => {
      if (Array.isArray(query[key])) {
        query[key].forEach(value => fullUrl.searchParams.append(key + '[]', value))
      } else {
        fullUrl.searchParams.append(key, query[key])
      }
    })
    return fullUrl.toString()
  },
  getCollection: function (url, query) {
    return new Promise(
      (resolve) => {
        const fullUrl = this._getFullUrl(url, query)
        axios.get(fullUrl)
          .then(response => {
            resolve(response.data.member)
          })
      }
    )
  },
  getPaginatedCollection: function (url, query) {
    return new Promise(
      (resolve) => {
        const fullUrl = this._getFullUrl(url, query)
        axios.get(fullUrl)
          .then(response => {
            const payload = {
              items: response.data.member,
              totalItems: response.data.totalItems
            }
            resolve(payload)
          })
      }
    )
  },
  get: async function (url) {
    const response = await axios.get(url)
    return response.data
  },
  post: async function (collectionUrl, post) {
    const response = await axios.post(collectionUrl, post, {headers: {'Content-Type': 'application/ld+json'}})
    return response.data
  },
  patch: async function (instance, patch) {
    const response = await axios.patch(instance['@id'], patch, {headers: {'Content-Type': 'application/merge-patch+json'}})
    this._writeAllProperties(instance, patch, response.data)
  },
  delete: async function (instance) {
    const response = await axios.delete(instance['@id'])
    // if 204, then soft delete
    if (response.status === 204) {
      instance.deletedAt = DateTime.now().toISO()
    }
  }
}

restClient.setupErrorNotifications()


const router = {
  navigateTo: function (route) {
    window.location.href = route
  },
  navigateToActiveProbe: function (probe) {
    this.navigateTo('/probes/active/' + iriToId(probe['@id']))
  }
}

const api = {
  getUser: function () {
    return window.user
  },
  getCurrentProbe: function () {
    return {
      probe: window.probe,
      reports: window.reports,
    }
  },
  get: function (id) {
    return restClient.get(id)
  },
  patch: function (instance, patch) {
    return restClient.patch(instance, patch)
  },
  getSpecimens: function (query = {}) {
    return restClient.getCollection('/api/specimens', query)
  },
  getPaginatedOrganisations: function (query) {
    return restClient.getPaginatedCollection('/api/organizations', query)
  },
  getPaginatedPatients: function (query) {
    return restClient.getPaginatedCollection('/api/patients', query)
  },
  getPaginatedAnimalKeepers: function (query) {
    return restClient.getPaginatedCollection('/api/animal_keepers', query)
  },
  postOrganization: function (payload) {
    return restClient.post('/api/organizations', payload)
  },
  postPatient: function (payload) {
    return restClient.post('/api/patients', payload)
  },
  postAnimalKeeper: function (payload) {
    return restClient.post('/api/animal_keepers', payload)
  },
  postProbe: function (payload) {
    return restClient.post('/api/probes', payload)
  },
  postObservation: function (payload) {
    return restClient.post('/api/observations', payload)
  }
}

export {api, router}
