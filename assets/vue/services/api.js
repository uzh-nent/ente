import {displayError} from "./notifiers";


export const iriToId = function (iri) {
  return iri.substr(iri.lastIndexOf('/') + 1)
}


const httpClient = {
  _handleError: async function (response) {
    let responseData

    try {
      responseData = await response.json()
    } catch (e) {
      // response body is not JSON
      console.log(e)
      displayError('Failed with unknown error')
      throw e
    }

    let errorText = response.status
    if (responseData && responseData.title && responseData.description) {
      errorText = responseData.title + ': ' + responseData.description
    } else if (responseData && responseData.detail) {
      errorText += ': ' + responseData.detail
    } else if (response.statusText) {
      errorText += ': ' + response.statusText
    }

    const error = new Error(errorText)
    error.response = response
    error.data = responseData

    console.log(error)
    displayError('Failed: ' + errorText)

    throw error
  },
  request: async function (url, init = {}) {
    let response

    try {
      response = await fetch(url, init)
    } catch (error) {
      if (error.name === 'AbortError') {
        // hide aborted errors (happens when navigating rapidly)
        return null
      }

      console.log(error)
      displayError('Failed: ' + error)

      throw error
    }

    if (!response.ok) {
      await this._handleError(response)
    }

    return response
  }
}

export const restRouter = {
  getQueryUrl: function (url, query) {
    const queryUrl = new URL(url, window.location.origin)
    Object.keys(query).forEach(key => {
      if (Array.isArray(query[key])) {
        query[key].forEach(value => queryUrl.searchParams.append(key + '[]', value))
      } else {
        queryUrl.searchParams.append(key, query[key])
      }
    })
    return queryUrl.toString()
  },
}

const xlsxMimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
const pdfMimeType = 'application/pdf'
export const restClient = {
  _jsonRequest: async function (url, options = {}, body = undefined) {
    const init = { ...options }
    if (body) {
      const normalizedBody = { ...body }
      for (const prop in normalizedBody) {
        if (Object.prototype.hasOwnProperty.call(normalizedBody, prop) && (normalizedBody[prop] === undefined || normalizedBody[prop] === '')) {
          normalizedBody[prop] = null
        }
      }
      init.body = JSON.stringify(normalizedBody)
    }

    const response = await httpClient.request(url, init)

    if (response.status === 204) {
      return null
    }

    return response
  },
  getCollection: async function (url, query = {}, options = {}) {
    const fullUrl = restRouter.getQueryUrl(url, query)
    const response = await this._jsonRequest(fullUrl, options)
    const responseData = await response.json()
    return responseData['member']
  },
  getPaginatedCollection: async function (url, query = {}, options = {}) {
    const fullUrl = restRouter.getQueryUrl(url, query)
    const response = await this._jsonRequest(fullUrl, options)
    const responseData = await response.json()
    return {
      items: responseData['member'],
      totalItems: responseData['totalItems']
    }
  },
  get: async function (url, query = {}, options = {}) {
    const fullUrl = restRouter.getQueryUrl(url, query)
    const response = await this._jsonRequest(fullUrl, options)
    return response.json()
  },
  getExcel: async function (url, query = {}, options = {}) {
    const fullUrl = restRouter.getQueryUrl(url, query)
    const response = await this._jsonRequest(fullUrl, {
      ...options,
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': xlsxMimeType,
        ...options.headers
      },
    })
    return response.blob()
  },
  post: async function (collectionUrl, post, options = {}) {
    const response = await this._jsonRequest(collectionUrl, {
      ...options,
      headers: {
        'Content-Type': 'application/ld+json',
        ...options.headers
      },
      method: 'POST'
    }, post)
    return response.json()
  },
  postPdf: async function (collectionUrl, post, options = {}) {
    const response = await this._jsonRequest(collectionUrl, {
      ...options,
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': pdfMimeType,
        ...options.headers
      },
      method: 'POST'
    }, post)

    return response.blob()
  },
  patch: async function (instance, patch, options = {}) {
    const response = await this._jsonRequest(instance['@id'], {
      ...options,
      headers: {
        'Content-Type': 'application/merge-patch+json',
        ...options.headers
      },
      method: 'PATCH'
    }, patch)
    const responseData = await response.json()

    // null values may not be delivered in response, hence check what values are only in patch and apply them
    for (const prop in patch) {
      if (Object.prototype.hasOwnProperty.call(patch, prop) && !Object.prototype.hasOwnProperty.call(responseData, prop)) {
        instance[prop] = patch[prop]
      }
    }

    for (const prop in responseData) {
      if (Object.prototype.hasOwnProperty.call(responseData, prop)) {
        instance[prop] = responseData[prop]
      }
    }
  },
  poll: async function (instance) {
    const response = await this._jsonRequest(instance['@id'] + "?poll=1")
    const responseData = await response.json()
    for (const prop in responseData) {
      if (Object.prototype.hasOwnProperty.call(responseData, prop)) {
        instance[prop] = responseData[prop]
      }
    }
  },
  delete: async function (instance, options = {}) {
    await this._jsonRequest(instance['@id'], {
      ...options,
      method: 'DELETE'
    })
  }
}
