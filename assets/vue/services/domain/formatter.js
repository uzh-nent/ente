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

export const formatObservation = function (observation, organisms, translator) {
  if (!observation) {
    return '-'
  }

  const observationLabel = formatDateTime(observation.effectiveAt) + " "
  const analysisLabel = translator('probe._analysis_type_short.' + observation.analysisType) + " ";

  if (observation.analysisType === 'IDENTIFICATION') {
    if (observation.interpretation !== 'POS') {
      return observationLabel + analysisLabel + translator('messages.failed');
    } else {
      const organism = organisms.find(s => s['@id'] === observation.organism)
      return observationLabel + organism?.displayName
    }
  } else {
    if (observation.interpretation) {
      const interpretationLabel = translator('observation._interpretation.' + observation.interpretation);
      return observationLabel + analysisLabel + interpretationLabel
    }

    return observationLabel + translator('messages.failed');
  }
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


export const formatOrganizationAddress = function (value) {
  if (!value) {
    return '-'
  }

  return value.name + "\n" + formatAddress(value)
}

export const formatPractitionerAddress = function (value) {
  if (!value) {
    return '-'
  }

  const nameValues = [value.title, value.givenName, value.familyName]
  return nameValues.filter(v => v).join(" ") + "\n" + formatAddress(value)
}

export const formatAddress = function (value) {
  if (!value) {
    return '-'
  }

  const fullAddress = [
    formatAddressLines(value),
    formatCityLine(value)
  ];

  return fullAddress.filter(e => e).join("\n")
}

export const formatAddressLines = function (value) {
  if (!value) {
    return '-'
  }

  return value.addressLines
}

export const formatCityLine = function (value) {
  if (!value) {
    return '-'
  }

  const cityLineValues = [value.postalCode, value.city]
  if (value.countryCode && value.countryCode !== 'CH') {
    cityLineValues.unshift(value.countryCode)
  }

  return cityLineValues.filter(e => e).join(" ")
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

export const formatAhvNumber = function (value, translator) {
  if (!value) {
    return '-'
  }

  if (value.length !== 13) {
    return value
  }

  return value.substring(0, 3) + "." + value.substring(3, 7) + "." + value.substring(7, 11) + "." + value.substr(11)
}


export const formatOrganizationShort = function (value) {
  if (!value) {
    return '-'
  }

  return [value.postalCode, value.name].filter(e => e).join(" ")
}


export const formatPractitionerName = function (value) {
  if (!value) {
    return '-'
  }

  return [value.title, value.givenName, value.familyName].filter(e => e).join(" ")
}

export const formatPractitionerShort = function (value) {
  if (!value) {
    return '-'
  }

  return [value.postalCode, formatPractitionerName(value)].filter(e => e).join(" ")
}


export const formatOrganism = function (organism) {
  if (!organism) {
    return '-'
  }

  let displayName = organism.displayName.trim()
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

export const formatProbeService = (probe, t) => {
  if (probe.laboratoryFunction === 'PRIMARY') {
    const identification = t('probe._analysis_type_short.EC');
    const types = probe.analysisTypes
      .map(tKey => t(`probe._analysis_type_short.${tKey}`))
      .join(', ');

    return `${identification} ${types}`.trim();
  }

  if (probe.laboratoryFunction === 'REFERENCE') {
    const identification = t('probe._analysis_type_short.IDENTIFICATION');
    const pathogenLabel = probe.pathogen
      ? t(`probe._pathogen.${probe.pathogen}`)
      : (probe.pathogenName || '');

    return `${identification} ${pathogenLabel}`.trim();
  }

  return '';
};


/**
 * Returns the specimen source label as plain text (no HTML).
 *
 * @param {object} probe
 * @param {(key: string) => string} t i18n translate function (e.g. this.$t)
 * @returns {string}
 */
export function formatSpecimenSourceText(probe, t) {
  if (!probe?.specimenSource) {
    return probe?.specimenSourceText ?? ''
  }

  const parts = [t(`probe._specimen_source.${probe.specimenSource}`)]

  if (probe.specimenSource === 'FOOD' && probe.specimenFoodType) {
    parts.push(t(`probe._specimen_food_type.${probe.specimenFoodType}`))
  } else if (probe.specimenSource === 'ANIMAL' && probe.specimenAnimalType) {
    parts.push(t(`probe._specimen_animal_type.${probe.specimenAnimalType}`))
  }

  parts.push(probe.specimenTypeText)

  return parts.filter(p => p).join(' ')
}
