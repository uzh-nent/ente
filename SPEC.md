# High-level specification


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

features v1.0:
- remove comment_report from report
- allow multiple addresses in report
- allow animal keeper to be optional
- fix typo in food type "fleisch", check other (including feed type)
- improve "0 Ergebnisse", "1 Ergebnisse" display
- elm report: ensure only LOINC, SNOMED, FOPH systems selectable
- vibrio: add PRC analysis result (Cholera-Toxin, tdh, trh parahämoliticus); allow to add/remove analysis (or "nicht durchgeführt")
- show number of next probe when creating probe
- fix bug: mensch erfasst, dann auf herkunft anderes geändert, nachher konte nicht speichern
- allow primärproben to select mediziner:in
- add help to patient address if countryCode != CH: should enter current address in CH
- mediziner:in, patient:in
- hide unused leading codes (or in general, stammdaten)
- reason for "identification not possible": "kein wachstum", "mischkultur", "andere"
- nur mit medizinische validierung dürfen berichte erstellt werden
- report: patientenfelder definieren (ahv-nummer, geburtsdatum), bemerkung kann weggelassen werden
- Textbausteine
- UI for report generation

features v1.1 data-model:
- wait feedback API
- wait urgent feedback users
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

