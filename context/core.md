# ns_timeline — Core facts

## Identity

| Item | Value |
|---|---|
| Extension key | `ns_timeline` |
| Composer name | `nitsan/ns-timeline` |
| Title (ext_emconf) / product name | TYPO3 Timeline & Events Extension / Timeline |
| Namespace | `NITSAN\NsTimeline\` → `Classes/` |
| Version (ext_emconf) | 14.1.0 (latest tag 14.0.1) |
| Repository | https://github.com/nitsan-technologies/ns_timeline (default branch `main`) |
| Licensing status | **Freemium** — this repo is the free version; a paid premium version exists in the T3Planet catalog (the NITSAN Free/Freemium sheet needs a Google login) |
| Premium version (T3Planet catalog) | "Timeline", €129, https://t3planet.de/en/typo3-timeline-extension |

## Compatibility

| Source | TYPO3 | PHP |
|---|---|---|
| `ext_emconf.php` | `12.0.0-14.9.99` | not declared |
| `composer.json` | `^12 \|\| ^13 \|\|^14` | not declared |
| ns_license 14.5.4 (dependency) | `12.4.18-14.9.99` | not declared (TYPO3 12.4 needs 8.1+) |

- Effective minimum is **TYPO3 12.4.18** (from ns_license) and **PHP 8.1**.

## Dependencies

| Package | Constraint | Why |
|---|---|---|
| `typo3/cms-core` | see above | core |
| `nitsan/ns-license` / `ns_license` | `^14.5.4` / `14.5.4-14.9.99` | TYPO3 Shop module + free-trial deep link (see `features/premium-cta.md`) |

Bundled frontend libraries (no package manager): Font Awesome 4 (`font-awesome/`, `fonts/`), animate.css.

## Tooling

- No PHPUnit, PHPStan, CS fixer or package.json in the repo. `composer.json` has a `.Build/` vendor/web-dir
  config for standalone installs.
- CI: `.github/workflows/ter-release.yml` (publishes tags to TER with tailor, PHP 7.4 runner) and an
  issue-triage workflow (T3Planet AI Bot).
- `.gitattributes` export-ignores `.crowdin.yml`, `crowdin.yml`, `.github/`, `Documentation.tmpl/`,
  `AGENTS.md`, `CLAUDE.md`, `context/`, `tasks/` and itself, so they are not in the GitHub/Composer archive or
  the TER package (the TER release uploads the GitHub tag archive).
- Translations: Crowdin (`.crowdin.yml`), sources `Resources/Private/Language/*.xlf` (`locallang.xlf`
  frontend + premium strip, `locallang_db.xlf` wizard/TCA, `locallang_flex.xlf` FlexForm).

## Conventions

- PHP: 4 spaces; only the event listeners, the `Form\*` classes and `PremiumStrip` declare `strict_types`.
- TypoScript: 4 spaces; Fluid and FlexForm: mixed tabs and spaces; XLF: mostly spaces.
- Frontend assets in lowercase folder `Resources/Public/css/`; backend CSS
  `Resources/Public/css/backend-premium-strip.css`.

## Known issues (existing, not fixed)

All code-read unless marked.

- **Site set constants never load:** `Sets/NsTimeline/constants.typoscript` imports
  `Configuration/TypoScript/Constants.typoscript` (capital C, the file is lowercase). The set setup works
  because it overrides the values from the set settings.
- Site set name `nitsan/ns-Timeline` (capital T) and its settings category label "All Slider".
- `ext_localconf.php` still reads the legacy `$_EXTCONF`.
- `NewContentElementPreviewRenderer::getFluidTemplateNew()` (v14) renders the template once and discards the
  output, then `render()` runs again; it also creates an unused `FlexFormTools` instance. With
  "Select Standard Variation" = None, the v14 preview has no template name.
- The preview listener identifier was `flexform-process` until 14.1.0, the same as in ns_revolution_slider;
  the duplicate silently removed the other extension's preview (seen live on 14.3.7). Renamed to
  `ns-timeline/flexform-process`.
- `tt_content.php` (v12/v13 branch) uses `addPiFlexFormValue()`; core moved this to `columnsOverrides`, which
  the v14 branch already does.
- `NewContentElement.tsconfig` adds the wizard item to group `common` and sets an empty `icon =`.
- Frontend CSS (`reset.css`, `animate.min.css`, `default.min.css`, `demo.css`, Font Awesome) is added to every
  page via `page.includeCSS`; the style partials and backend preview templates add more `<link>` tags inline.
- Templates and `DefaultProcessor` read `alignment`, `animationslist`, `selectTheme`, `themeColor`,
  `newsOrderDirection`, which the free FlexForm does not have (premium leftovers). `DefaultProcessor` has an
  unused `$defaultOrderings` and a "Deafult" typo.
- `locallang.xlf` contains the trans-unit id `type.videoUrl` twice.
- Both `crowdin.yml` and `.crowdin.yml` exist with the same content; `Documentation.tmpl/` is an unused
  documentation skeleton.
- README badges show v13.0.1 / TYPO3 13 and GPL-3.0; `composer.json` uses the deprecated SPDX id `GPL-2.0+`.
