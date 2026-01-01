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

Bericht Auftraggeber
- Titel Bericht erfassen  
- Empfänger (default: Auftraggeber)
- Kommentare erfassen  
- Medizinisch berechtigt auf Bericht setzen

features v1.0:
- Rechnungen
- Statistik (bis 2025)
- Statistik (ab 2025)

features v?:
- login using azureAD
- add attribution to stammdaten
- add practitioner orderer in case of reference lab
- create service request online by customer
- improve ELM api integration (sync value sets, add patient reporting type such as anonymous to leading codes)
- technical:
  - frontend share person, address, contact forms
  - run integration test with API request

