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

  const cityLineValues = [value.postalCode, value.city]
  if (value.countryCode && value.countryCode !== 'CH') {
    cityLineValues.unshift(value.countryCode)
  }
  const cityLine = cityLineValues.filter(e => e)
  if (cityLine.length === 0) {
    return '-'
  }

  return cityLine.join(" ")
}

export const formatAddress = function (value) {
  if (!value) {
    return '-'
  }

  const fullAddress = [value.addressLines]

  const cityLineValues = [value.postalCode, value.city]
  if (value.countryCode && value.countryCode !== 'CH') {
    cityLineValues.unshift(value.countryCode)
  }
  const cityLine = cityLineValues.filter(e => e)
  if (cityLine.length > 0) {
    fullAddress.push(cityLine.join(" "))
  }

  return fullAddress.filter(e => e).join("\n")
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


export const formatOrganizationShort = function (value) {
  if (!value) {
    return '-'
  }

  return [value.postalCode, value.name].filter(e => e).join(" ")
}


export const formatOrganism = function (organism) {
  if (!organism) {
    return '-'
  }

  let displayName = organism.displayName
  if (displayName.endsWith(" (organism)")) {
    displayName = displayName.substring(0, displayName.length - 11)
  }

  return displayName
}

export const formatAnimalKeeperShort = function (value) {
  if (!value) {
    return '-'
  }

  return [value.postalCode, value.name].filter(e => e).join(" ")
}

export const formatPatientShort = function (value) {
  if (!value) {
    return '-'
  }

  return [value.ahvNumber, value.givenName, value.familyName, value.postalCode, value.city].filter(e => e).join(" ")
}
