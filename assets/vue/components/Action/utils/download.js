export const excelMimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
export const pdfMimeType = 'application/pdf'

export const downloadFile = async (response, mimeType, filename) => {
  const blob = new Blob([response], {type: mimeType,})
  const downloadUrl = window.URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = downloadUrl
  link.download = filename
  link.click()
  window.URL.revokeObjectURL(downloadUrl)
}
