import moment from 'moment'

const formatDate = function (value) {
  if (!value) {
    return '-'
  }

  return moment(value).format('DD.MM.YYYY')
}

const formatDateTime = function (value) {
  if (!value) {
    return '-'
  }

  return moment(value).format('DD.MM.YYYY HH:mm')
}

export { formatDate, formatDateTime }
