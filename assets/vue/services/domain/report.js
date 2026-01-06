export const createResults = function (probe, observations, organisms, translator) {
  const observationResults = observations.map(o => {
    if (o.analysisType === 'IDENTIFICATION') {
      const pathogenLabel = translator('probe._pathogen.' + probe.pathogen);

      let result = translator('report._interpretation.NONE')
      if (o.interpretation) {
        const organism = o.organism ? organisms.find(org => org['@id'] === o.organism) : null
        const resultPrefix = translator('report._interpretation.' + o.interpretation)
        result = resultPrefix + " " + (organism ? organism.displayName : pathogenLabel)
      }
      return {
        analysis: translator('report.service.identification_of') + " " + pathogenLabel,
        method: mapToIdentificationMethodCode(probe.pathogen),
        result,
      }
    } else {
      const analysisType = translator('report._analysis_type.' + o.analysisType);

      let result = translator('report._interpretation.NONE')
      if (o.interpretation) {
        const resultPrefix = translator('report._interpretation.' + o.interpretation)
        result = resultPrefix + " " + analysisType
      }
      return {
        analysis: analysisType + " (PCR)",
        method: mapToPcrMethodCode(o.analysisType),
        result,
      }
    }
  })

  const otherResults = probe.analysisTypes
    .filter(analysisType => !observations.find(o => o.analysisType === analysisType))
    .map(analysisType => {
      const result = translator('report._interpretation.PENDING')

      if (analysisType === 'IDENTIFICATION') {
        const pathogenLabel = translator('probe._pathogen.' + probe.pathogen);
        return {
          analysis: translator('report.service.identification_of') + " " + pathogenLabel,
          method: mapToIdentificationMethodCode(probe.pathogen),
          result,
        }
      } else {
        const analysisTypeLabel = translator('report._analysis_type.' + analysisType);
        return {
          analysis: analysisTypeLabel + " (PCR)",
          method: mapToPcrMethodCode(analysisTypeLabel),
          result,
        }
      }
  })

  return observationResults.concat(otherResults)
}

const mapToPcrMethodCode = function (analysisType) {
  switch (analysisType) {
    case 'EC_EAEC':
      return 58;
    case 'EC_STEC':
    case 'EC_EPEC':
    case 'EC_ETEC':
    case 'EC_EIEC':
      return 57;
    default:
      return null
  }
}

const mapToIdentificationMethodCode = function (pathogen) {
  switch (pathogen) {
    case 'SALMONELLA':
      return 51;
    case 'SHIGELLA':
      return 52;
    case 'CAMPYLOBACTER':
      return 53;
    case 'VIBRIO_CHOLERAE':
      return 55;
    case 'YERSINIA':
      return 56;
    case 'LISTERIA_MONOCYTOGENES':
      return 59;
    default:
      return null
  }

  // TODO no E.Coli
}
