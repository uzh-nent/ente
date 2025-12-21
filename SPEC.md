# High-level specification

## Next steps

entities:
    Login-user with password (shortname)

then:
    generate migrations
    implement login


## Offene Fragen

Erfassung Probe:
- Geburtstag Patient immer vorhanden, oder? -> +Wohnort
- Auftraggeber: Tel/Fax zu mehrzeiliges Feld Kontaktinformationen? -> bei versand resultat lebensmittel
- Patient: Versicherung / Versicherungs-Nr erfassen? -> no
- Tier: Halter strukturiert erfassen (Vorname/Nachname, Adresse)? + Tierbezeichnung
- Visum automatisch

Erfassung Resultat:
- Tests für Patient: "folgt" Nachweis? Ist der Bericht unterschiedlich? -> folgt bei Tests die länger dauern
- Berichte: Generiert von, Datum, Resulat, Kommentar -> Visum automatisch, nicht alle sind Generierungsberechtigt
- Negativer Befund Erreger setzen -> organisms ist ausreichend

Organisatorisch:
- Vom 1sten bis am 5ten Januar geschieht; denn es wäre natürlich sauber, ab dem neuen Jahr alle Daten am neuen Ort zu haben.
- Antrags-PDF anpassen werden sollen/können, um danach die Erfassung nach dem BAG einfacher zu haben (konkret: AHV-Nummer, Materialauswahl). 


## Datenverwaltung

Stammdaten:
- Daten zu Auftraggeber, zu Patienten, zu Halter
- Kopie davon wird jeweils in Probe hineinkopiert (nachträgliche Änderungen an Stammdaten, z.B. Adresse, hat keinen Effekt auf Probedaten)

Medizinische Stammdaten:
- Nicht in DB sind Herkunft, Organism (fixe Liste, programmiert)
- Leading LOINC (Organism, Organism group, Specimen group, Specimen, Interpretation group)
- Organism (Organism group, System, Code, Display name) -> system ist LOINC/SNOMED/CUSTOM
- Specimen (Specimen group, System, Code, Display name) -> system ist LOINC/SNOMED/CUSTOM (CUSTOM für Andere/Unbekannt)
- Interpretation (Interpretation group, System, Code, Display name) -> system ist LOINC/SNOMED/CUSTOM (CUSTOM für Kein Wachstum)


## Erfassung Probe

Auswahl "Referenzlabor" oder "Primärdiagnostik"  
Der Labor-/Stammnummer
Setzen von Auftraggeber anhand von Stammdaten  
Herkunft:
 - Bei Mensch: Auswahl Patient anhand von Stammdaten
 - Bei Tier: Auswahl Halter anhand von Stammdaten
 - Sonstiges: Futtermittel, etc
 
Setzen von Material (nach BAG Specimen), Checkbox "Isolat" (default aktivieren)  
Setzen der Typisierung (Organism) (Referenzlabor) oder der Tests (Primärlabor)  
Setzen Eingang- und Ansatzdatum  
Visum atumatisch
Herunterladen des Arbeitsblattes


## Resultatserfassung

Setzen Ausgangsdatum  
Diagnose POS / NEG / Kein Wachstum (nach BAG Interpretation)  
Erreger (nach BAG Organism)  


## Übermittlung BAG

Leading LOINC setzen (eingeschränkt anhand Typisierung), definiert welche anderen Felder ausgefüllt werden müssen  
Felder vorausgefüllt anhand der Angaben vorher, Validierung dass korrektes Code-System verwendet wird


## Bericht Auftraggeber

Titel Bericht erfassen  
Empfänger (default: Auftraggeber)
Kommentare erfassen  
Medizinisch berechtigt auf Bericht setzen
=> in useraccount vormerken wer berichtssignaturberechtigt
