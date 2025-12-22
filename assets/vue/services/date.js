export const dateStringToISOTimestamp = (dateString, hours, min, sec) => {
  const date = new Date(dateString)
  date.setHours(hours)
  date.setMinutes(min)
  date.setSeconds(sec)
  return date.toISOString()
}
