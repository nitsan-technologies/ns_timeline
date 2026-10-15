# ns_timeline — Session state

## 2026-10-15 — ns_license integration + premium strip + agent context

- Branch `feature/ns-license-premium-strip` from `main` (3c8b497).
- Added `nitsan/ns-license ^14.5.4` / `ns_license 14.5.4-14.9.99` (effective minimum now TYPO3 12.4.18).
  Version 14.0.1 → 14.1.0.
- Premium strip with "Start Free Trial" → ns_license module `?trial=ns_timeline#free-section` above the
  timeline preview in the Page module and above the FlexForm in the edit form (tab "General"), team-wide
  wording and design (see `features/premium-cta.md`).
- Renamed the preview listener identifier `flexform-process` → `ns-timeline/flexform-process`; the duplicate
  had removed ns_revolution_slider's Page module preview.
- Verified live on TYPO3 14.3.7 classic with a temporary Timeline element on page 1 and a temporary
  system-maintainer admin (both removed; `settings.php` restored byte-identical). PHP 8.4 and 8.1 lint clean.
- Created agent context (`AGENTS.md`, `CLAUDE.md`, `context/`, `tasks/`) and `.gitattributes`; no previous
  context existed. Added "ns_timeline 48" to the resolver priority lists of the other NITSAN extensions.
- Open: ns_license 14.5.4 not yet published; v12/v13 not tested; frontend only code-read.
