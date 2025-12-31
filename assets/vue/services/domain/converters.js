export const probeConverter = {
  writeOrdererOrg: function (organization) {
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
  writeOrdererPrac: function (pracitioner) {
    return {
      ordererPrac: pracitioner['@id'],
      ordererPracTitle: pracitioner.title,
      ordererPracGivenName: pracitioner.givenName,
      ordererPracFamilyName: pracitioner.familyName,
      ordererPracAddressLines: pracitioner.addressLines,
      ordererPracCity: pracitioner.city,
      ordererPracPostalCode: pracitioner.postalCode,
      ordererPracCountryCode: pracitioner.countryCode,
      ordererPracContact: pracitioner.contact,
    }
  },
  writeAnimalKeeper: function (animalKeeper) {
    return {
      animalKeeper: animalKeeper['@id'],
      animalKeeperName: animalKeeper.name,
      animalKeeperAddressLines: animalKeeper.addressLines,
      animalKeeperCity: animalKeeper.city,
      animalKeeperPostalCode: animalKeeper.postalCode,
      animalKeeperCountryCode: animalKeeper.countryCode,
    }
  },
  writePatient: function (patient) {
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
  reconstructOrdererOrgOrganization: function (probe) {
    return {
      name: probe.ordererOrgName,
      addressLines: probe.ordererOrgAddressLines,
      city: probe.ordererOrgCity,
      postalCode: probe.ordererOrgPostalCode,
      countryCode: probe.ordererOrgCountryCode,
      contact: probe.ordererOrgContact,
    }
  },
  reconstructOrdererPracPractitioner: function (probe) {
    return {
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
    return {
      name: probe.animalKeeperName,
      addressLines: probe.animalKeeperAddressLines,
      city: probe.animalKeeperCity,
      postalCode: probe.animalKeeperPostalCode,
      countryCode: probe.animalKeeperCountryCode,
    }
  },
  reconstructPatient: function (probe) {
    return {
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
