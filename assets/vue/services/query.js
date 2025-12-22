import {dateStringToISOTimestamp} from "./date";

export const createQuery = function (queryTemplate, numericProps, textProps, dateTimeProps, filter, order) {
  let query = createFilteredQuery(queryTemplate, numericProps, textProps, dateTimeProps, filter)
  return createOrderedQuery(query, order)
}

export const createFilteredQuery = function (queryTemplate, numericProps, textProps, dateTimeProps, filter) {
  let query = {...queryTemplate}

  numericProps.concat(...textProps).filter(p => filter[p])
    .forEach(p => {
      query[p] = filter[p]
    })

  dateTimeProps.forEach(prop => {
    if (filter[prop + '[before]']) {
      let dateString = filter[prop + '[before]'];
      query[prop + '[before]'] = dateStringToISOTimestamp(dateString, 23, 59, 59)
    }
    if (filter[prop + '[after]']) {
      let dateString = filter[prop + '[after]'];
      query[prop + '[after]'] = dateStringToISOTimestamp(dateString, 0, 0, 0)
    }
  })

  return query
}

export const createOrderedQuery = function (queryTemplate, order) {
  let query = {...queryTemplate}

  order.forEach(order => {
    query['order[' + order.property + ']'] = order.order
  })

  return query
}
