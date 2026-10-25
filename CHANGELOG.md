# Changelog

## 1.0.0 – 2026-10-25

First public release.

- Font Awesome from public CDNs (cdnjs, jsDelivr, use.fontawesome.com, BootstrapCDN) replaced by Joomla's local copy – on all pages or EDocman pages only.
- EDocman document pages: second `<h1>` (mobile header) becomes a `<div>`.
- EDocman lists: `<h1>` headlines in short descriptions become paragraphs with a configurable CSS class.
- Optional rewriting of EDocman page titles (prefix removal, suffixes for categories and documents).
- Joomla 4, 5 and 6 (also 4.0/4.1, where `setApplication()` does not exist yet); backend labels, explanatory info boxes and field hints in English and German; Joomla update server.
- Local Font Awesome file is only used if it contains the `@font-face` rules (vendor file on Joomla 6, `joomla-fontawesome.css` on Joomla 4/5), so icons never turn into empty boxes.
- CDN links are detected with `http(s)://` or protocol-relative `//`, in double or single quotes.
