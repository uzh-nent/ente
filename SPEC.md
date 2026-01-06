# High-level specification

create probe:
- create reference (identification) probes for Salmonella, Shigella, Yersinia, Listeria monocytogenes, Vibrio cholerae, Escherichia coli, Campylobacter
- create primary (presence) probes over E.coli STEC, EPEC, ETEC, EIEC/Shingella, EAEC
- attribute probes to organizations and/or practitioners
- define specimen for humans, animals, food, feed, environment, laboratory strain & others
- track service time (received, started analysis, stopped analysis)

perform analysis & report results:
- generate worksheet for analysis
- track result (pos/neg/failed, comment, organism in case of identification probes)
- submit ELM-report Salmonella, Shingella, Listeria, Vibrio Cholerae and STEC (EHEC)
- generate PDF-report of results for customers

manage data:
- view active and all probes
- manage master data (organizations, practitioners, patients, animal keeperts)
- manage reference data (leading codes, organisms, specimens)
- manage config (users)
- use pandemic placeholder of FHOP

special cases:
- Shingella, Yersinia, Listeria monocytogenes have pre-printed table on worksheet
- Vibrio reference probes additionally check for presence of Cholera-Toxin, tdh and/or trh


## Datenverwaltung

Stammdaten:
- zu Auftraggeber, zu Mediziner, zu Patienten, zu Halter
- Kopie davon wird jeweils in Probe hineinkopiert (nachträgliche Änderungen an Stammdaten, z.B. Adresse, hat keinen Effekt auf Probedaten)

Medizinische Stammdaten:
- Leading LOINC, Organism, Specimen
- Nicht in DB sind Herkunft, Pathogen, Interpretation (fixe Liste, programmiert)

Probedaten:
- Probe speichert den Auftrag, das Specimen und den administrativen Ablauf
- Observation für das Resultat
- ElmReport für (ELM BAG API)[https://elm.wiki.bagapps.ch/], Report für PDF


## Future features

urgent:
- Textbausteine
- UI for report generation
- allow multiple addresses in report
- report: patientenfelder definieren (ahv-nummer, geburtsdatum)

priority 2:
- elm report: ensure only LOINC, SNOMED, FOPH systems selectable
- vibrio: add PRC analysis result (Cholera-Toxin, tdh, trh parahämoliticus); allow to add/remove analysis (or "nicht durchgeführt")

priority 3:
- show number of next probe when creating probe
- allow proben to select mediziner:in

features v1.0:
- priority 1-3
- add help to patient address if countryCode != CH: should enter current address in CH
- hide unused leading codes (or in general, stammdaten)
- reason for "identification not possible": "kein wachstum", "mischkultur", "andere"
- nur mit medizinischer validierung dürfen berichte erstellt werden
- editing organization/practitioner/animal keeper: pre-select, allow to remove selection
- improve "0 Ergebnisse", "1 Ergebnisse" display
- refactor linking entities:
  - reuse code of forms (see component branch)
  - "link" entity in search, copy inside entity
  - only edit copied data, not stammdaten itself
  - propose to sync into master data if edited
  - propose to sync from master data if not edited
  - (also ensures the entity is always shown)

features v1.1 data-model:
- refactor address / contact partial forms
	-	add department, webpage to organization
	-	add structured contact info (email, tel) to animal keeper / practitioner / organization / patient
	-	add GLN to practitioner
	-	add BER to organization
	-	add UID to animal keeper
	-	ensure address shown completely everywhere
	-	report tel number of patient

features v1.2:
- wait feedback users
- wait stable data model
- Find probes from stammdaten
- Improve AllProbes view: filter by service, entity; add compact view of observation
- Rechnungen
- Statistik (bis 2025)
- Statistik (ab 2025)

features v?:
- login using azureAD
- improve stammdaten: add attribution, apply changes to all active probes
- add practitioner orderer in reference lab scenario
- let customer create service request online
- digitalize additional data from laborblatt
- improve ELM api integration (sync value sets, add patient reporting type such as anonymous to leading codes)
- technical:
  - run integration test with API request

