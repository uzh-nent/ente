import {formatAddressLines, formatCityLine, formatPractitionerName} from "./formatter";

export const probeConverter = {
  copyFromOrganization: function (organization) {
    if (!organization) {
      return {}
    }

    return {
      ordererOrg: organization['@id'],
      ordererOrgBer: organization.ber,
      ordererOrgUid: organization.uid,
      ordererOrgName: organization.name,
      ordererOrgAddressLines: organization.addressLines,
      ordererOrgCity: organization.city,
      ordererOrgPostalCode: organization.postalCode,
      ordererOrgCountryCode: organization.countryCode,
      ordererOrgEmail: organization.email,
      ordererOrgPhone: organization.phone,
      ordererOrgContact: organization.contact,
    }
  },
  copyFromPractitioner: function (practitioner) {
    if (!practitioner) {
      return {}
    }

    return {
      ordererPrac: practitioner['@id'],
      ordererPracGln: practitioner.gln,
      ordererPracTitle: practitioner.title,
      ordererPracGivenName: practitioner.givenName,
      ordererPracFamilyName: practitioner.familyName,
      ordererPracAddressLines: practitioner.addressLines,
      ordererPracCity: practitioner.city,
      ordererPracPostalCode: practitioner.postalCode,
      ordererPracCountryCode: practitioner.countryCode,
      ordererPracEmail: practitioner.email,
      ordererPracPhone: practitioner.phone,
      ordererPracContact: practitioner.contact,
    }
  },
  copyFromAnimalKeeper: function (animalKeeper) {
    return {
      animalKeeper: animalKeeper['@id'],
      animalKeeperBer: animalKeeper.ber,
      animalKeeperUid: animalKeeper.uid,
      animalKeeperName: animalKeeper.name,
      animalKeeperAddressLines: animalKeeper.addressLines,
      animalKeeperCity: animalKeeper.city,
      animalKeeperPostalCode: animalKeeper.postalCode,
      animalKeeperCountryCode: animalKeeper.countryCode,
      animalKeeperEmail: animalKeeper.email,
      animalKeeperPhone: animalKeeper.phone,
      animalKeeperContact: animalKeeper.contact,
    }
  },
  copyFromPatient: function (patient) {
    return {
      patient: patient['@id'],
      patientAhvNumber: patient.ahvNumber,
      patientBirthDate: patient.birthDate,
      patientGender: patient.gender,
      patientGivenName: patient.givenName,
      patientFamilyName: patient.familyName,
      patientAddressLines: patient.addressLines,
      patientCity: patient.city,
      patientPostalCode: patient.postalCode,
      patientCountryCode: patient.countryCode,
      patientEmail: patient.email,
      patientPhone: patient.phone,
      patientContact: patient.contact,
    }
  },
  reconstructOrdererOrg: function (probe) {
    if (!probe.ordererOrg) {
      return null
    }

    return {
      '@id': probe.ordererOrg,
      ber: probe.ordererOrgBer,
      uid: probe.ordererOrgUid,
      name: probe.ordererOrgName,
      addressLines: probe.ordererOrgAddressLines,
      city: probe.ordererOrgCity,
      postalCode: probe.ordererOrgPostalCode,
      countryCode: probe.ordererOrgCountryCode,
      email: probe.ordererOrgEmail,
      phone: probe.ordererOrgPhone,
      contact: probe.ordererOrgContact,
    }
  },
  reconstructOrdererPrac: function (probe) {
    if (!probe.ordererPrac) {
      return null
    }

    return {
      '@id': probe.ordererPrac,
      gln: probe.ordererPracGln,
      title: probe.ordererPracTitle,
      givenName: probe.ordererPracGivenName,
      familyName: probe.ordererPracFamilyName,
      addressLines: probe.ordererPracAddressLines,
      city: probe.ordererPracCity,
      postalCode: probe.ordererPracPostalCode,
      countryCode: probe.ordererPracCountryCode,
      email: probe.ordererPracEmail,
      phone: probe.ordererPracPhone,
      contact: probe.ordererPracContact,
    }
  },
  reconstructAnimalKeeper: function (probe) {
    if (!probe.animalKeeper) {
      return null
    }

    return {
      '@id': probe.animalKeeper,
      ber: probe.animalKeeperBer,
      uid: probe.animalKeeperUid,
      name: probe.animalKeeperName,
      addressLines: probe.animalKeeperAddressLines,
      city: probe.animalKeeperCity,
      postalCode: probe.animalKeeperPostalCode,
      countryCode: probe.animalKeeperCountryCode,
      email: probe.animalKeeperEmail,
      phone: probe.animalKeeperPhone,
      contact: probe.animalKeeperContact,
    }
  },
  reconstructPatient: function (probe) {
    if (!probe.patient) {
      return null
    }

    return {
      '@id': probe.patient,
      ahvNumber: probe.patientAhvNumber,
      birthDate: probe.patientBirthDate,
      gender: probe.patientGender,
      givenName: probe.patientGivenName,
      familyName: probe.patientFamilyName,
      addressLines: probe.patientAddressLines,
      city: probe.patientCity,
      postalCode: probe.patientPostalCode,
      countryCode: probe.patientCountryCode,
      email: probe.patientEmail,
      phone: probe.patientPhone,
      contact: probe.patientContact,
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
