export const probeConverter = {
  writeOrderer: function (organization) {
    return {
      orderer: organization['@id'],
      ordererName: organization.name,
      ordererAddressLines: organization.addressLines,
      ordererCity: organization.city,
      ordererPostalCode: organization.postalCode,
      ordererCountryCode: organization.countryCode,
      ordererContact: organization.contact,
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
  reconstructOrdererOrganization: function (probe) {
    return {
      name: probe.ordererName,
      addressLines: probe.ordererAddressLines,
      city: probe.ordererCity,
      postalCode: probe.ordererPostalCode,
      countryCode: probe.ordererCountryCode,
      contact: probe.ordererContact,
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
