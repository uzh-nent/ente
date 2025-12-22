import moment from 'moment'

export const formatDate = function (value) {
  if (!value) {
    return '-'
  }

  return moment(value).format('DD.MM.YYYY')
}

export const formatDateTime = function (value) {
  if (!value) {
    return '-'
  }

  return moment(value).format('DD.MM.YYYY HH:mm')
}

export  const formatAddressCity = function (value) {
  if (!value) {
    return '-'
  }

  let city = value.postalCode + " " + value.city
  if (value.countryCode !== 'CH') {
    city += " (" + value.countryCode + ")"
  }

  return city
}

export  const formatPersonName = function (value) {
  if (!value) {
    return '-'
  }

  return [this.patient.givenName, this.patient.familyName].filter().join(" ")
}

