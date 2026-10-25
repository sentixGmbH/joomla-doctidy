# DocTidy – Joomla-Systemplugin

Kleines, konfigurierbares Plugin, das die Seitenausgabe aufräumt – nutzbar auf jeder Joomla-Seite, besonders mit EDocman.

[English documentation](README.md) · [Changelog](CHANGELOG.md) · Lizenz: GPL-2.0-or-later

| Funktion | Standard | Wirkung |
|---|---|---|
| Font Awesome lokal | an | Ersetzt Font-Awesome-Links von öffentlichen CDNs (cdnjs, jsDelivr, use.fontawesome.com, BootstrapCDN) durch die lokale Kopie, die Joomla mitbringt. **Datenschutz:** keine IP-Übertragung an Dritte. Wählbar: alle Seiten oder nur EDocman. |
| Eine H1 auf Dokumentseiten | an | EDocman 4.x gibt den Dokumenttitel zweimal als H1 aus (Desktop + Handy). Der Handy-Titel wird ein `<div>`. |
| Keine H1 in Dokumentlisten | an | H1-Überschriften in Dokumentbeschreibungen (Listen) werden `<p class="doctidy-headline">` (Klasse einstellbar). |
| EDocman-Seitentitel anpassen | aus | Entfernt ein Präfix (z. B. `Meine Seite - `) und hängt je nach Ansicht einen Zusatz an. |

Alle Ersetzungen sind eng gefasst: Ändert EDocman sein HTML, greifen sie einfach nicht mehr – die Seite bleibt dann unverändert. EDocman-Dateien werden nicht verändert, das Plugin übersteht also EDocman-Updates.

## Voraussetzungen

- Joomla 4.x, 5.x oder 6.x (nicht Joomla 3)
- PHP 7.4 oder neuer
- Für „Font Awesome lokal“: die Joomla-eigene Kopie unter `media/vendor/fontawesome-free/` (ab Joomla 4 vorhanden). Fehlt sie, lässt das Plugin die Links unverändert.

## Installation

1. `plg_system_doctidy_x.y.z.zip` aus dem [neuesten Release](../../releases/latest) herunterladen.
1. Joomla-Backend → **System → Installieren → Erweiterungen** → ZIP hochladen.
2. **System → Plugins** → „System - DocTidy“ öffnen, Funktionen wählen, **aktivieren**, speichern.
3. Prüfen: Seitenquelltext (Strg+U) nach `cdnjs` bzw. `fontawesome` durchsuchen – es sollte nur noch `/media/vendor/fontawesome-free/…` vorkommen.

Alternativ per Befehlszeile (Joomla-Wurzelverzeichnis): `php cli/joomla.php extension:install --path=/pfad/plg_system_doctidy_x.y.z.zip`, danach im Backend aktivieren.

## Einstellungen im Detail

Alle Einstellungen findest du im Joomla-Backend unter **System → Plugins → „System - DocTidy“**. Änderungen wirken sofort nach dem Speichern. Ist der Joomla-Cache eingeschaltet, solltest du ihn danach leeren (**System → Cache leeren**).

### 1. Font Awesome lokal

| | |
|---|---|
| **Im Backend** | „Font Awesome lokal“ (Englisch: „Font Awesome locally“) |
| **Werte** | Ja / Nein |
| **Standard** | Ja |

**Worum geht es?** Font Awesome ist eine Sammlung kleiner Symbole (Pfeile, Download-Wolke, Auge usw.), die viele Erweiterungen nutzen. EDocman lädt sie zum Beispiel von einem fremden Server, einem sogenannten CDN (Content Delivery Network), etwa `cdnjs.cloudflare.com`. Bei jedem Seitenaufruf erfährt dieser Server dann die IP-Adresse des Besuchers. Das ist ohne Einwilligung datenschutzrechtlich heikel.

**Was macht die Einstellung?** Sie ersetzt in der fertigen Seite den Link zum CDN durch die Kopie, die Joomla ab Version 4 ohnehin mitbringt (`/media/vendor/fontawesome-free/`). Die Symbole sehen gleich aus, kommen aber vom eigenen Server. Erkannt werden die verbreiteten CDNs: cdnjs, jsDelivr, use.fontawesome.com und BootstrapCDN. Bindet eine Seite Font Awesome mehrfach ein, bleibt nur ein Link übrig.

**Wann ausschalten?** Nur wenn ein Template bewusst eine bestimmte, neuere Font-Awesome-Version vom CDN braucht und einzelne Symbole fehlen. Siehe dazu die Hinweise unten.

### 2. Anwenden auf

| | |
|---|---|
| **Im Backend** | „Anwenden auf“ („Apply on“). Erscheint nur, wenn „Font Awesome lokal“ eingeschaltet ist. |
| **Werte** | „Alle Seiten“ / „Nur EDocman-Seiten“ |
| **Standard** | Alle Seiten |

**Was macht die Einstellung?** Sie legt fest, wo der CDN-Link ersetzt wird.
- **Alle Seiten:** empfohlen. Erfasst auch andere Erweiterungen, Module oder Templates, die Font Awesome von einem CDN laden.
- **Nur EDocman-Seiten:** Der Ersatz greift nur auf Seiten der Komponente EDocman (Kategorien, Listen, Dokumente). Sinnvoll, wenn auf anderen Seiten absichtlich eine eigene CDN-Version genutzt wird.

### 3. Eine H1 auf Dokumentseiten

| | |
|---|---|
| **Im Backend** | „Eine H1 auf Dokumentseiten“ („One H1 on document pages“) |
| **Werte** | Ja / Nein |
| **Standard** | Ja |

**Worum geht es?** Die H1 ist die Hauptüberschrift einer Seite. Suchmaschinen und Vorleseprogramme für Sehbehinderte erwarten genau eine davon. EDocman 4 schreibt den Dokumenttitel aber zweimal als H1 in die Seite: einmal für die Desktop-Ansicht und einmal für die Handy-Ansicht. Sichtbar ist jeweils nur einer.

**Was macht die Einstellung?** Der Titel im Handy-Kopf wird ein normales Blockelement (`<div class="document-title">`). Die CSS-Klasse bleibt gleich, deshalb sieht die Seite genauso aus wie vorher.

**Wann ausschalten?** Praktisch nie. Bei älteren EDocman-Versionen ohne Handy-Kopf bewirkt die Einstellung einfach nichts.

### 4. Keine H1 in Dokumentlisten

| | |
|---|---|
| **Im Backend** | „Keine H1 in Dokumentlisten“ („No H1 in document lists“) |
| **Werte** | Ja / Nein |
| **Standard** | Ja |

**Worum geht es?** Wer in EDocman die Kurzbeschreibung eines Dokuments mit einer Überschrift beginnt, erzeugt dort meist eine H1. In den Dokumentlisten, etwa einer Jahresübersicht, steht dann für jedes Dokument eine weitere H1. Eine Seite kann so leicht 5 bis 20 Hauptüberschriften haben.

**Was macht die Einstellung?** In den Listen werden diese H1-Überschriften zu Absätzen mit einer CSS-Klasse (`<p class="doctidy-headline">`). Ihr Aussehen bestimmt dann das CSS deines Templates, siehe Beispiel unten. Ohne passendes CSS erscheinen sie als normaler Text in der Grundschrift. Die Kurzbeschreibung in EDocman selbst bleibt unverändert, angepasst wird nur die Ausgabe.

**Wann ausschalten?** Wenn eure Kurzbeschreibungen keine Überschriften enthalten, kannst du die Einstellung eingeschaltet lassen. Sie bewirkt dann einfach nichts.

### 5. CSS-Klasse für diese Überschriften

| | |
|---|---|
| **Im Backend** | „CSS-Klasse für diese Überschriften“ („CSS class for these headlines“). Erscheint nur, wenn Einstellung 4 eingeschaltet ist. |
| **Werte** | freier Text, eine oder mehrere Klassen mit Leerzeichen getrennt |
| **Standard** | `doctidy-headline` |

**Was macht die Einstellung?** Sie bestimmt den Klassennamen der Absätze aus Einstellung 4. Ändern solltest du ihn nur, wenn dein Template schon eine passende Klasse hat. Bei YOOtheme ist das zum Beispiel `uk-h4`. Bleibt das Feld leer, wird `doctidy-headline` verwendet.

### 6. EDocman-Seitentitel anpassen

| | |
|---|---|
| **Im Backend** | „EDocman-Seitentitel anpassen“ („Rewrite EDocman page titles“) |
| **Werte** | Ja / Nein |
| **Standard** | Nein |

**Worum geht es?** Der Seitentitel ist der Text im Browser-Tab und die blaue Überschrift in den Google-Ergebnissen. EDocman bildet ihn oft als „Seitenname - Dokumentname“, zum Beispiel „Meine Seite - Newsletter 2026-10-18“. Wichtig für Google ist aber, dass der aussagekräftige Teil vorne steht.

**Was macht die Einstellung?** Auf EDocman-Seiten wird ein festgelegter Anfang (Präfix) entfernt und hinten ein Zusatz angehängt. Die Einstellungen 7 bis 9 legen fest, was genau passiert. Seiten mit einem eigenen Titel aus dem Menüpunkt bleiben unverändert, solange sie nicht mit dem Präfix beginnen.

### 7. Präfix entfernen

| | |
|---|---|
| **Im Backend** | „Präfix entfernen“ („Remove prefix“). Erscheint nur, wenn Einstellung 6 eingeschaltet ist. |
| **Standard** | leer |

**Was eintragen?** Den Textanfang, der weg soll, **genau so, wie er im Titel steht, einschließlich Trennzeichen und Leerzeichen**. Am einfachsten kopierst du ihn aus dem Browser-Tab. Beispiel: Steht dort „Meine Seite - 2026“, trägst du `Meine Seite - ` ein, mit dem Leerzeichen am Ende.

- **Mit Präfix:** Nur Titel, die genau so beginnen, werden angepasst. Das ist die sichere Variante.
- **Leer:** Alle EDocman-Titel bekommen den Zusatz, auch solche, die schon gut sind. Nur sinnvoll, wenn EDocman-Seiten bisher gar keinen Seitennamen im Titel haben.

### 8. Zusatz für Kategorien

| | |
|---|---|
| **Im Backend** | „Zusatz für Kategorien“ („Suffix for categories“) |
| **Standard** | leer |

**Was eintragen?** Text, der an Kategorie- und Listenseiten angehängt wird, **mit führendem Trennzeichen**. Beispiel: ` – Berichte | Meine Seite`. Aus „Meine Seite - 2026“ wird so „2026 – Berichte | Meine Seite“. Leer = kein Zusatz.

### 9. Zusatz für Dokumente

| | |
|---|---|
| **Im Backend** | „Zusatz für Dokumente“ („Suffix for documents“) |
| **Standard** | leer |

**Was eintragen?** Dasselbe für einzelne Dokumentseiten, meist in der Einzahl. Beispiel: ` – Bericht | Meine Seite`. Aus „Meine Seite - Newsletter 2026-10-18“ wird „Newsletter 2026-10-18 – Bericht | Meine Seite“.

> **Tipp für gute Titel:** Halte die ganzen Titel unter etwa 60 Zeichen, sonst kürzt Google sie in den Suchergebnissen.

### Beispiel-Einstellungen

| Einstellung | Seite mit Titel-Umbau (Beispiel) | Typische Seite ohne Titel-Umbau |
|---|---|---|
| Font Awesome lokal | Ja | Ja |
| Anwenden auf | Alle Seiten | Alle Seiten |
| Eine H1 auf Dokumentseiten | Ja | Ja |
| Keine H1 in Dokumentlisten | Ja | Ja |
| CSS-Klasse | `uk-h4` | `doctidy-headline` |
| Seitentitel anpassen | Ja | Nein |
| Präfix entfernen | `Meine Seite - ` | – |
| Zusatz für Kategorien | ` – Berichte \| Meine Seite` | – |
| Zusatz für Dokumente | ` – Bericht \| Meine Seite` | – |

### Prüfen, ob alles wirkt

1. Eine EDocman-Seite im Browser öffnen und mit **Strg+U** den Quelltext anzeigen.
2. Mit **Strg+F** suchen:
   - `cdnjs`: kein Treffer, dann ist Font Awesome lokal.
   - `<h1`: genau ein Treffer, dann gibt es eine Hauptüberschrift.
   - `<title>`: zeigt den Seitentitel.

## CSS für die Listen-Überschriften (Beispiel)

```css
.edocman-description-details .doctidy-headline { font-size: 20px; font-weight: 700; margin: 4px 0 8px; }
```

## Hinweise

- Font Awesome: Die lokale Joomla-Kopie ist Version 6.x (Joomla 5/6) bzw. 5.x/6.x (Joomla 4). Die von EDocman genutzten Symbole sind darin enthalten; bei sehr neuen Symbolnamen auf älteren Joomla-4-Seiten kurz die Darstellung prüfen.
- Deinstallation: System → Erweiterungen verwalten → „System - DocTidy“ → Deinstallieren. Es bleiben keine Daten zurück.

## Selbst bauen

`python build.py` erzeugt `dist/plg_system_doctidy_<version>.zip`. Ein Git-Tag `v<version>` erstellt das Paket automatisch als GitHub-Release.

## Lizenz

GNU General Public License Version 2 oder später – siehe [LICENSE](LICENSE). © 2026 sentix GmbH.
