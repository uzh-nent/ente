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
- E. coli (primary) STEC, EPEC, ETEC and EIEC are all done using the same multi-PCR. This detects stx 1 (Shigatoxin 1), stx 2 (Shigatoxin 2), eaeA (Intimin-Gen), estA (hitze-stabiles Enterotoxin), eltA (hitze-labiles Enterotoxin) and ipaH (invasives Plasmid-Antigen H). If stx 1 or stx 2 are positive, then it is STEC (independent of eaeA). If eaeA is positive, then it is EPEC. If estA or eltA are positive, then it is ETEC. If ipaH is positive, then it is EIEC/Shigella (as ipaH is very close to Shigella, could also mean that it is actually Shigella).
- E. coli (primary) EAEC (=EAggEC) needs a dedicated PCR. The PCR checks for pCVD432 Plasmides (EAEC I) and agg3C (EAEC II); either of these is positive, means EAEC.
- E. coli (reference): Isolates strain, applies tests to each strain. Only the positive strain(s) are tracked in NENT. Tested is the multi-PCR (STEC, EPEC, ETEC and EIEC), no dedicated identification processus. Only those results of the multi-PCR are evaluated that were positive in the primary probe.
- Listeria are first only checked for their group, and this is what is on the initial reports. Afterward, a precise gen sequencing is done, and a dedicated report is written. This dedicated report is outside the scope of ENTE.
- Some Shigella types may also contain stx1 or stx2, and therefore these toxin checks may also be done.
- Vibrio: Cholera-toxin is checked for, and if it is negative, then tdh and trh are checked, too. Usually, all three tests are initiated at the same time, as in practice the probes that reach NENT are all toxin-negative. If toxin is positive, then an initial report is sent to BAG & customer, and identification is initiated. If tdh or trx is positive, then organism must be Vibrio parahömolythicus (and hence no identification necessary).

ELM:
- documentation by the BAG at https://elm.wiki.bagapps.ch/, with the formal spec at https://fhir.ch/ig/ch-elm/
- Salmonella: `Salmonella sp serovar [Type] in Isolate`. This requires an organism from [`sal_org_complete`](https://fhir.ch/ig/ch-elm/ValueSet-ch-elm-results-sal-org-complete.html). If it is not in this set, choose leading code `Salmonella sp antigenic formula [Identifier] in Isolate by Agglutination (TEXT)` and submit as text, and contact the [BAG](mailto:infreport@bag.admin.ch) to ask for an extension of `sal_org_complete`.
- Shigella: `Shigella sp [Presence] in Specimen by Organism specific culture`. This requires an organism from `shi_org`. For Shigella that are not yet in `shi_org`: Submit more coarse-grained variant that is inside that set.
- Listeria: `Listeria sp identified in Specimen by Organism specific culture`. This requires an organism from `lis_org`. ENTE only submits the high-level group 1-4.
- Vibrio: `Vibrio sp identified in Specimen by Organism specific culture`. This requires an organism from `chol_org`, and this list is expected to be complete. If the Cholera-toxin is positive, *additionally* report it using `Vibrio cholerae toxin Ag [Presence] in Isolate`.
- STEC: `Escherichia coli Stx1 toxin stx1 gene [Presence] in Stool by NAA with probe detection` or `Escherichia coli Stx2 toxin stx2 gene [Presence] in Stool by NAA with probe detection`. Note that STEC may have both genes positive, in that both leading codes are submitted (hence two reports).
- Pandemic case: Use FOPH leading codes `https://fhir.ch/ig/ch-elm/CodeSystem-ch-elm-foph-code-reserve.html`
- In case submission over ELM fails, need to submit over https://web.em.bag.admin.ch/. In case submission was wrong and needs to be corrected, can contact BAG over [HIN E-Mail](mailto:infreport@hin.ch).

ENTE design decisions:
- probe is for a single strain under a single function. So when shifting from primary to reference laboratory function, or when multiple strains are contained in a single probe, then multiple entries for the same probe is created.


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

features v1.3:
- reason for "identification not possible": "kein wachstum", "mischkultur", "andere". check if other default texts useful
- refactor address / contact partial forms
    -	add structured contact info (email, tel) to animal keeper / practitioner / organization / patient
    -	add GLN to practitioner
    -	add UID/BER to organization / animal keeper
    -	report tel number of patient
- interne textbausteine
- add monocytogenes to primary probes, add stx1 stx2 as separate pos/neg, polish custom pathogen case
- Statistik (until 2025) -> separate P/N probes, allow to choose date
- Statistik (from 2025) -> allow to export csv from table search result, with observation results inline
- invoicing:
  - for all primary probes, track whether "ambulant" or "stationär"; when "ambulant" invoice patient, else invoice customer
  - cost is 119.7 per PCF; 47.7 for multi-pcr additional results
  - need to be able to correct price to any price
  - for ambulant, need to be able to generate rückforderungsbeleg

features v?:
- improve stammdaten: add attribution, when changed propose to apply to all probes, include standard text into stammdaten (hence frontend edit etc)
- improve reference data: allow single reference to be in multiple collections, then prevent double storage
- add practitioner orderer in reference lab scenario
- let customer create service request online
- more structured result tracking (e.g. STEC E. coli Stx1, Stx2 separate tracking) / digitize additional data from laborblatt
- improve ELM api integration (sync value sets, add patient reporting type such as anonymous to leading codes)
- technical:
  - run integration test with API request

