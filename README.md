# DocTidy – Joomla system plugin

**Tidies up the page output of Joomla sites – especially with EDocman:** loads Font Awesome from Joomla's local copy instead of a public CDN (privacy / GDPR), keeps a single `<h1>` on EDocman pages and can rewrite EDocman page titles.

[Deutsche Anleitung](README.de.md) · [Changelog](CHANGELOG.md) · License: GPL-2.0-or-later

| Feature | Default | What it does |
|---|---|---|
| Font Awesome locally | on | Replaces Font Awesome stylesheets from public CDNs (cdnjs, jsDelivr, use.fontawesome.com, BootstrapCDN) with the copy Joomla ships in `media/vendor/fontawesome-free/`. No visitor IP address is sent to a third party. |
| One H1 on document pages | on | EDocman 4.x prints the document title twice as `<h1>` (desktop + mobile header). The mobile one becomes a `<div>`. |
| No H1 in document lists | on | `<h1>` headlines inside document descriptions in EDocman lists become `<p class="doctidy-headline">` (class configurable). |
| Rewrite EDocman page titles | off | Removes a prefix (e.g. `My Site - `) from EDocman page titles and appends a suffix per view. |

DocTidy only post-processes the rendered HTML (`onAfterRender`). It does **not** modify EDocman or template files, so it survives extension updates. Every replacement is narrow: if the markup changes, it simply stops matching and the page stays as it is.

## Requirements

- Joomla **4.x, 5.x or 6.x** (not Joomla 3)
- PHP **7.4** or newer
- For "Font Awesome locally": Joomla's own copy in `media/vendor/fontawesome-free/` (present since Joomla 4). If it is missing, the CDN links are left untouched.

EDocman is optional – the Font Awesome feature works on any Joomla site.

## Installation

1. Download `plg_system_doctidy_x.y.z.zip` from the [latest release](../../releases/latest).
2. Joomla backend → **System → Install → Extensions** → upload the ZIP.
3. **System → Plugins** → open **"System - DocTidy"**, choose the features, **enable** the plugin, save.
4. Clear the Joomla cache if caching is enabled.

Command line alternative (Joomla root): `php cli/joomla.php extension:install --path=/path/to/plg_system_doctidy_x.y.z.zip`, then enable the plugin in the backend.

Updates are announced in the Joomla backend via the update server in this repository.

## Settings

| Setting | Values (default) | Description |
|---|---|---|
| **Font Awesome locally** | Yes / No (**Yes**) | Replace CDN-hosted Font Awesome with the local Joomla copy. If a page includes Font Awesome several times, only one link remains. |
| **Apply on** | All pages / EDocman pages only (**All pages**) | Where the CDN link is replaced. Choose "EDocman pages only" if other pages intentionally use a CDN version. |
| **One H1 on document pages** | Yes / No (**Yes**) | Turns the second, mobile `<h1 class="document-title">` on EDocman document pages into a `<div>` with the same class – no visual change. |
| **No H1 in document lists** | Yes / No (**Yes**) | Converts `<h1>` headlines in document short descriptions on list pages into paragraphs. Your short descriptions themselves are not changed. |
| **CSS class for these headlines** | text (**`doctidy-headline`**) | Class of the replacement paragraphs, e.g. `uk-h4` for YOOtheme. |
| **Rewrite EDocman page titles** | Yes / No (**No**) | Enables the three title fields below (EDocman pages only). |
| **Remove prefix** | text (empty) | Exact beginning of the title to remove, including separator and trailing space, e.g. `My Site - `. **Only titles starting with this text are rewritten**; pages with their own title stay untouched. Empty = rewrite all EDocman titles. |
| **Suffix for categories** | text (empty) | Appended on category/list pages, e.g. ` – Reports \| My Site`. |
| **Suffix for documents** | text (empty) | Appended on document pages, e.g. ` – Report \| My Site`. |

Example: with prefix `My Site - ` and document suffix ` – Report | My Site`, the title "My Site - Newsletter 2026-10-18" becomes "Newsletter 2026-10-18 – Report | My Site".

### Example CSS

```css
.edocman-description-details .doctidy-headline { font-size: 20px; font-weight: 700; margin: 4px 0 8px; }
```

### Verify

Open an EDocman page, view the source (Ctrl+U) and search for `cdnjs` (should not be found), `<h1` (exactly one) and `<title>`.

## Notes

- Joomla 5/6 ship Font Awesome 6.x; Joomla 4 ships 5.x or 6.x depending on the version. The icons used by EDocman are included – on older Joomla 4 sites, quickly check pages that use very new icon names.
- Uninstall: System → Manage → Extensions → "System - DocTidy" → Uninstall. No data is left behind.

## Building from source

```bash
python build.py
```

creates `dist/plg_system_doctidy_<version>.zip` (version taken from `plugin/doctidy.xml`). Pushing a tag `v<version>` builds the ZIP automatically and attaches it to a GitHub release.

## License

GNU General Public License version 2 or later – see [LICENSE](LICENSE). © 2026 sentix GmbH.
