export const sanitizeSearchFilter = function (filter) {
  let query = {}

  for (const [key, value] of Object.entries(filter)) {
    if (value !== null && value !== undefined && value !== '') {
      query[key] = value.trim()
    }
  }

  return query
}

export const sanitizeFilter = function (filter) {
  let query = {}

  for (const [key, value] of Object.entries(filter)) {
    if (Array.isArray(value) && value.length === 0) {
      continue;
    }

    if (value === null || value === undefined || value === '') {
      continue;
    }

    query[key] = value
  }

  return query
}

export const orderFilter = function (order) {
  let query = {}

  order.forEach(order => {
    query['order[' + order.property + ']'] = order.order
  })

  return query
}
