export const checkHasMedicalValidation = function (users) {
  return getCurrentUser(users)?.medicalValidation
}

export const getCurrentUser = function (users) {
  const shortname = document.getElementById("shortname").textContent
  return users.find(u => u.shortname === shortname)
}
