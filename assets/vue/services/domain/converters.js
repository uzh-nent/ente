import {formatAddressLines, formatCityLine, formatPractitionerName} from "./formatter";

export const probeConverter = {
  copyFromOrganization: function (organization) {
    if (!organization) {
      return {}
    }

    return {
      ordererOrg: organization['@id'],
      ordererOrgName: organization.name,
      ordererOrgAddressLines: organization.addressLines,
      ordererOrgCity: organization.city,
      ordererOrgPostalCode: organization.postalCode,
      ordererOrgCountryCode: organization.countryCode,
      ordererOrgContact: organization.contact,
    }
  },
  copyFromPractitioner: function (practitioner) {
    if (!practitioner) {
      return {}
    }

    return {
      ordererPrac: practitioner['@id'],
      ordererPracTitle: practitioner.title,
      ordererPracGivenName: practitioner.givenName,
      ordererPracFamilyName: practitioner.familyName,
      ordererPracAddressLines: practitioner.addressLines,
      ordererPracCity: practitioner.city,
      ordererPracPostalCode: practitioner.postalCode,
      ordererPracCountryCode: practitioner.countryCode,
      ordererPracContact: practitioner.contact,
    }
  },
  copyFromAnimalKeeper: function (animalKeeper) {
    return {
      animalKeeper: animalKeeper['@id'],
      animalKeeperName: animalKeeper.name,
      animalKeeperAddressLines: animalKeeper.addressLines,
      animalKeeperCity: animalKeeper.city,
      animalKeeperPostalCode: animalKeeper.postalCode,
      animalKeeperCountryCode: animalKeeper.countryCode,
    }
  },
  copyFromPatient: function (patient) {
    return {
      patient: patient['@id'],
      patientBirthDate: patient.birthDate,
      patientAhvNumber: patient.ahvNumber,
      patientGender: patient.gender,
      patientGivenName: patient.givenName,
      patientFamilyName: patient.familyName,
      patientAddressLines: patient.addressLines,
      patientCity: patient.city,
      patientPostalCode: patient.postalCode,
      patientCountryCode: patient.countryCode,
    }
  },
  reconstructOrdererOrg: function (probe) {
    if (!probe.ordererOrg) {
      return null
    }

    return {
      '@id': probe.ordererOrg,
      name: probe.ordererOrgName,
      addressLines: probe.ordererOrgAddressLines,
      city: probe.ordererOrgCity,
      postalCode: probe.ordererOrgPostalCode,
      countryCode: probe.ordererOrgCountryCode,
      contact: probe.ordererOrgContact,
    }
  },
  reconstructOrdererPrac: function (probe) {
    if (!probe.ordererPrac) {
      return null
    }

    return {
      '@id': probe.ordererPrac,
      title: probe.ordererPracTitle,
      givenName: probe.ordererPracGivenName,
      familyName: probe.ordererPracFamilyName,
      addressLines: probe.ordererPracAddressLines,
      city: probe.ordererPracCity,
      postalCode: probe.ordererPracPostalCode,
      countryCode: probe.ordererPracCountryCode,
      contact: probe.ordererPracContact,
    }
  },
  reconstructAnimalKeeper: function (probe) {
    if (!probe.animalKeeper) {
      return null
    }

    return {
      '@id': probe.animalKeeper,
      name: probe.animalKeeperName,
      addressLines: probe.animalKeeperAddressLines,
      city: probe.animalKeeperCity,
      postalCode: probe.animalKeeperPostalCode,
      countryCode: probe.animalKeeperCountryCode,
    }
  },
  reconstructPatient: function (probe) {
    if (!probe.patient) {
      return null
    }

    return {
      '@id': probe.patient,
      birthDate: probe.patientBirthDate,
      ahvNumber: probe.patientAhvNumber,
      gender: probe.patientGender,
      givenName: probe.patientGivenName,
      familyName: probe.patientFamilyName,
      addressLines: probe.patientAddressLines,
      city: probe.patientCity,
      postalCode: probe.patientPostalCode,
      countryCode: probe.patientCountryCode,
    }
  },
}

export const addressConverter = {
  createFromOrganization: function (organization) {
    return {
      name: organization.name,
      addressLines: formatAddressLines(organization),
      cityLine: formatCityLine(organization),
    }
  },
  createFromPractitioner: function (practitioner) {
    return {
      name: formatPractitionerName(practitioner),
      addressLines: formatAddressLines(practitioner),
      cityLine: formatCityLine(practitioner),
    }
  },
  createFromText: function (text) {
    const lines = text.split('\n')
    return {
      name: lines[0] ?? "",
      addressLines: lines.slice(1, lines.length - 1).join("\n"),
      cityLine: lines[lines.length - 1] ?? "",
    }
  }
}
