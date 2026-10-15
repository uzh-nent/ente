export const createLineItems = function (probe, tarif, translator) {
  const lineItemBase = {
    tarif: tarif.tarifCode,
    tpw: tarif.tpw,
  }
  const invoicedEntries = []
  return probe.analysisTypes.map(analysisType => {
    const matchingEntry = findFirstMatchingEntry(probe, analysisType, tarif.entries, invoicedEntries)
    if (!matchingEntry) {
      const service = analysisType === 'IDENTIFICATION' ?
        (translator('report.service.identification_of') + " " + (probe.pathogen ? translator('probe._pathogen.' + probe.pathogen) : probe.pathogenName)) :
        translator('report._analysis_type.' + analysisType)

      return {
        ...lineItemBase,
        service,
      }
    }

    invoicedEntries.push(matchingEntry.position)

    return {
      ...lineItemBase,
      position: matchingEntry.position,
      service: matchingEntry.service,
      tp: matchingEntry.tp,
    }
  })
}

const findFirstMatchingEntry = function (probe, analysisType, entries, invoicedEntries) {
  for (const entry of entries) {
    if (entry.pathogen !== probe.pathogen || entry.analysisType !== analysisType) {
      continue;
    }

    if (Array.isArray(entry.precondition) && !entry.precondition.some(p => invoicedEntries.includes(p))) {
      continue;
    }

    return entry
  }

  return null
}

