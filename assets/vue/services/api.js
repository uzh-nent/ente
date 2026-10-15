import axios from 'axios'
import {displayError} from "./notifiers";


export const iriToId = function (iri) {
  return iri.substr(iri.lastIndexOf('/') + 1)
}

const xlsxMimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
const pdfMimeType = 'application/pdf'
export const restClient = {
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
    // undefined values would not be serialized, hence transform to null
    const instance = { ...payload}
    for (const prop in payload) {
      if (Object.prototype.hasOwnProperty.call(payload, prop) && (payload[prop] === undefined || payload[prop] === "")) {
        instance[prop] = null
      }
    }
    return instance
  },
  _writeAllProperties: function (instance, patch, responseData) {
    // null values are not delivered in response, hence take them from patch
    for (const prop in patch) {
      if (Object.prototype.hasOwnProperty.call(patch, prop) && patch[prop] === null) {
        instance[prop] = undefined
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
        if (query[key] === null) {
          fullUrl.searchParams.append("exists[" + key + "]", 0)
        } else {
          fullUrl.searchParams.append(key, query[key])
        }
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
  getExcel: async function (url) {
    const response = await axios.get(url, {responseType: 'blob', headers: {'Accept': xlsxMimeType}})
    return response.data
  },
  postPdf: async function (url, post) {
    const normalizedPost = this._normalizePayload(post)
    const response = await axios.post(url, normalizedPost, {responseType: 'blob', headers: {'Accept': pdfMimeType}})
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
  },
  delete: async function (instance) {
    await axios.delete(instance['@id'])
  },
}

restClient.setupErrorNotifications()


