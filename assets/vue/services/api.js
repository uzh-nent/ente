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
  _normalizePayload: function (payload) {
    // null values are not delivered in response, hence take them from patch
    const instance = { ...payload}
    for (const prop in payload) {
      if (Object.prototype.hasOwnProperty.call(payload, prop) && payload[prop] === undefined) {
        instance[prop] = null
      }
    }
    return instance
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
  getCollection: async function (url, query) {
    const fullUrl = this._getFullUrl(url, query)
    const response = await axios.get(fullUrl)
    return response.data.member
  },
  getPaginatedCollection: async function (url, query) {
    const fullUrl = this._getFullUrl(url, query)
    const response = await axios.get(fullUrl)
    return {
      items: response.data.member,
      totalItems: response.data.totalItems
    }
  },
  get: async function (url) {
    const response = await axios.get(url)
    return response.data
  },
  poll: async function (instance) {
    const response = await axios.get(instance['@id'] + "?poll=1")
    this._writeAllProperties(instance, {}, response.data)
  },
  post: async function (collectionUrl, post) {
    const normalizedPost = this._normalizePayload(post)
    const response = await axios.post(collectionUrl, normalizedPost, {headers: {'Content-Type': 'application/ld+json'}})
    return response.data
  },
  patch: async function (instance, patch) {
    const normalizedPatch = this._normalizePayload(patch)
    const response = await axios.patch(instance['@id'], normalizedPatch, {headers: {'Content-Type': 'application/merge-patch+json'}})
    this._writeAllProperties(instance, normalizedPatch, response.data)
  }
}

restClient.setupErrorNotifications()


const router = {
  probesView: function (query = {}) {
    return restClient._getFullUrl('/probes/all', query)
  },
  probeView: function (probe) {
    return '/probes/all/' + iriToId(probe['@id']) + '/view'
  },
  probeActiveView: function (probe) {
    return '/probes/active/' + iriToId(probe['@id']) + '/view'
  },
  probeWorksheetPdf: function (probe) {
    return '/probes/active/' + iriToId(probe['@id']) + '/worksheet.pdf'
  },
  reportPdf: function (report) {
    return '/reports/' + iriToId(report['@id']) + '/download/' + report.filename
  }
}

const preloadApi = {
  getNewProbe: function () {
    return {
      specimens: window.specimens.member,
    }
  },
  getActiveProbes: function () {
    return {
      activeProbes: window.activeProbes.member,
    }
  },
  getAllProbes: function () {
    return {
      organisms: window.organisms.member,
    }
  },
  getViewActiveProbe: function () {
    return {
      probe: window.probe,

      observations: window.observations.member,
      elmReports: window.elmReports.member,
      reports: window.reports.member,

      specimens: window.specimens.member,
      leadingCodes: window.leadingCodes.member,
      organisms: window.organisms.member,

      users: window.users.member,
      standardTexts: window.standardTexts.member,
    }
  },
}

const api = {
  get: function (id) {
    return restClient.get(id)
  },
  poll: function (entity) {
    return restClient.poll(entity)
  },
  patch: function (instance, patch) {
    return restClient.patch(instance, patch)
  },
  getPaginatedProbes: function (query) {
    query['collections'] = 1
    return restClient.getPaginatedCollection('/api/probes', query)
  },
  getPaginatedOrganisations: function (query) {
    return restClient.getPaginatedCollection('/api/organizations', query)
  },
  getPaginatedPractitioners: function (query) {
    return restClient.getPaginatedCollection('/api/practitioners', query)
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
  postPractitioner: function (payload) {
    return restClient.post('/api/practitioners', payload)
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
  },
  postElmReport: function (payload) {
    return restClient.post('/api/elm_reports', payload)
  },
  postReport: function (payload) {
    return restClient.post('/api/reports', payload)
  }
}

export {preloadApi, api, router}
