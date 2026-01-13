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
- organisms structured by SNOMED code system, with custom NENT system if not available in SNOMED.
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
- E. coli reference probes are equal to primary laboratory STEC, EPEC, ETEC and EIEC tests (but not EAEC).
- Pandemic case: create probe with custom pathogen, submit over elm with FOPH leading code


## Business primer

context:
- the BAG assigns reference laboratories, which identify the precise organism in identification 
- NENT is the reference laboratory for the 7 organisms mentioned above
- NENT uses ISO 17025:2018 to certify their quality management
- Some organisms need to be reported to the BAG if found according to [VMüK](https://www.fedlex.admin.ch/eli/cc/2015/892/de)

analysis:
- presence tests check using PCR if some organism is contained in the probe
- presence tests are becoming less relevant, as universal methods based on gen-sequencing exist that detect many organisms at the same time. this is less manual work, and many practices do it directly themselves. however, this may be less precise, and cannot distinguish alive and dead organisms.
- identification tests look at different dimensions (e.g. which gas the organisms produces). some dimensions are less relevant, and only lead to variants of the organisms (but not their own independently tracked organism).

NENT analysis:
- E. coli (primary): STEC, EPEC, ETEC and EIEC are all done using the same PCR. EAEC (=EAggEC) needs a dedicated PCR. EIEC E. coli are very close to Shigella, hence a positive EIEC could also mean a Shigella infection. 
- E. coli (reference): Isolates strain, applies tests to each strain. Only the positive strain(s) are tracked in NENT. Tested is the multi-PCR (STEC, EPEC, ETEC and EIEC), no dedicated identification processus. Only those results of the multi-PCR are evaluated that were positive in the primary probe.
- Listeria are first only checked for their group, and this is what is on the initial reports. Afterwards, a precise gen sequencing is done, and a dedicated report is written. This dedicated report is outside the scope of ENTE.
- Vibrio: Cholera-toxin is checked for, and if it is negative, then tdh and trh are checked, too. Usually, all three tests are initiated at the same time, as in practice the probes that reach NENT are all toxin-negative. If toxin is positive, then an initial report is sent to BAG & customer, and identification is initiated. If tdh or trx is positive, then organism must be Vibrio parahömolythicus (and hence no identification necessary).

ELM:
- documentation by the BAG at https://elm.wiki.bagapps.ch/, with the formal spec at https://fhir.ch/ig/ch-elm/
- before sending a new pathogen, ask BAG for content-tests
- report corrections are to be sent over E-Mail to infreport@hin.ch

ELM leading code per pathogen:
- Salmonella: `Salmonella sp serovar [Type] in Isolate`. This requires an organism from [`sal_org_complete`](https://fhir.ch/ig/ch-elm/ValueSet-ch-elm-results-sal-org-complete.html). Else, choose leading code `Salmonella sp antigenic formula [Identifier] in Isolate by Agglutination (TEXT)` and submit as text.
- Shigella: `Shigella sp [Presence] in Specimen by Organism specific culture`. This requires an organism from `shi_org`. For Shigella that are not yet in `shi_org`: Submit more coarse-grained variant that is inside that set.
- Listeria: `Listeria sp identified in Specimen by Organism specific culture`. This requires an organism from `lis_org`. ENTE only submits the high-level group 1-4.
- Vibrio: `Vibrio sp identified in Specimen by Organism specific culture`. This requires an organism from `chol_org`, and this list is expected to be complete. To be defined whether toxin positive needs to be reported, too.
- STEC: `Escherichia coli Stx1 toxin stx1 gene [Presence] in Stool by NAA with probe detection` or `Escherichia coli Stx2 toxin stx2 gene [Presence] in Stool by NAA with probe detection`. Note that STEC may have both genes positive, in that case leading code `Escherichia coli shiga-like toxin DNA` is submitted.
- Pandemic case: Use FOPH leading codes `https://fhir.ch/ig/ch-elm/CodeSystem-ch-elm-foph-code-reserve.html`
- note that leading codes are not 100% accurate at the moment, and organism sets are not complete, but both are subject to improvement.


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

features v1.2:
- elm report: ensure only LOINC, SNOMED, FOPH systems selectable.
- show number of next probe when creating probe
- allow proben to select mediziner:in, verify mediziner:in is shown. check that appears as expected on PDF/ELM report
- add help to patient address if countryCode != CH: should enter current address in CH
- hide unused leading codes (or in general, stammdaten)
- reason for "identification not possible": "kein wachstum", "mischkultur", "andere". check if other default texts useful
- nur mit medizinischer validierung dürfen berichte erstellt werden
- improve "0 Ergebnisse", "1 Ergebnisse" display; show more than 10
- refactor address / contact partial forms
	-	add department, webpage to organization
	-	add structured contact info (email, tel) to animal keeper / practitioner / organization / patient
	-	add GLN to practitioner
	-	add BER to organization
	-	add UID to animal keeper
	-	ensure address shown completely everywhere
	-	report tel number of patient
- refactor linking entities:
  - reuse code of forms (see component branch)
  - "link" entity in search, copy inside entity
  - only edit copied data, not stammdaten itself
  - propose to sync into master data if edited
  - propose to sync from master data if not edited
  - (also ensures the entity is always shown)
- Find probes from stammdaten
- Improve AllProbes view: filter by service, entity; add compact view of observation

features v1.3:
- wait feedback users
- Rechnungen
- Statistik (bis 2025)
- Statistik (ab 2025)

features v?:
- login using azureAD
- improve stammdaten: add attribution, when changed propose to apply to all probes, include standard text into stammdaten (hence frontend edit etc)
- change pathogen service request; must store pathogen in observation, adapt all usages
- add practitioner orderer in reference lab scenario
- let customer create service request online
- more structured result tracking (e.g. STEC E. coli Stx1, Stx2 separate tracking) / digitize additional data from laborblatt
- improve ELM api integration (sync value sets, add patient reporting type such as anonymous to leading codes)
- technical:
  - run integration test with API request

