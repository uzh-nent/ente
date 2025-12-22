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

export const formatAddressCity = function (value) {
  if (!value) {
    return '-'
  }

  let city = [value.postalCode, value.city].filter(e => e).join(" ")
  if (value.countryCode !== 'CH') {
    city += " (" + value.countryCode + ")"
  }

  return city
}

export const formatPersonName = function (value) {
  if (!value) {
    return '-'
  }

  return [value.givenName, value.familyName].filter(e => e).join(" ")
}

export const formatPatientName = function (value, translator) {
  if (!value) {
    return '-'
  }

  const genderSuffix = value.gender ?
    " (" + translator('patient._gender_short.' + value.gender) + ")" : null;

  return formatPersonName(value) + genderSuffix
}

