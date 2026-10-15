export const sortOrganisms = (organisms) => {
  organisms.sort((a, b) => {
    return a.displayName.localeCompare(b.displayName)
  })
}
