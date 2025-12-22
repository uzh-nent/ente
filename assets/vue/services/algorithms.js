export const apiIdDictionary = (list) => {
  const result = {}
  list.forEach(entry => result[entry['@id']] = entry)
  return result
}
