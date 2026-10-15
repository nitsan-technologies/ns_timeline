# ns_timeline — Architecture

One content element (CType `nstimeline`, FLUIDTEMPLATE + data processor), no Extbase plugin, no own tables, no
backend module. Backend additions: Page module preview, FormEngine resolver and the premium strip.

## Configuration

| File | What |
|---|---|
| `ext_localconf.php` | Legacy `$_EXTCONF` read; FormEngine `nodeResolver` 1792058932 (`flexFormNoTabsContainer`, priority 48) |
| `Configuration/TCA/Overrides/tt_content.php` | CType item `nstimeline` after `textmedia` (group `default`), `showitem` with `pi_flexform`; FlexForm via `addPiFlexFormValue()` (v12/v13) or `columnsOverrides` (v14) |
| `Configuration/FlexForms/ns_timeline.xml` | One sheet `sDEF` "Timeline Fields": `normalVariation` (reload, items from `ItemsProcFunc::getNormalStylesList`), `dateOpposite`, section `section` → container (`title`, `text` RTE, `link`, `date`, `customIcon` unless style-2) |
| `Configuration/page.tsconfig` → `TsConfig/NewContentElement.tsconfig` | Wizard item in group `common`. Loaded globally by core since v12; the site set imports it again |
| `Configuration/TCA/Overrides/sys_template.php` | Static template "Timeline" (`Configuration/TypoScript`) |
| `Configuration/TypoScript/` | Constants `ns_timeline.website.{settings,paths}.*`; setup: global `page.includeCSS`, `tt_content.nstimeline` FLUIDTEMPLATE (`FilesProcessor` + `DefaultProcessor`), `lib.enableThemeColor` / `lib.themeColor` |
| `Configuration/Sets/NsTimeline/` | Site set `nitsan/ns-Timeline`; settings `ns_timeline.view.*`, `ns_timeline.configuration.{enableThemeColor,themeColor}`; setup imports the classic setup and overrides the theme colour |
| `Configuration/Services.yaml` | No `Classes/*` sweep. Listeners `ns-timeline/flexform-process` and `ns-timeline/premium-strip-preview`; `Backend\PremiumStrip`; `Form\*` (autoconfigured as FormEngine nodes) |
| `Configuration/Icons.php` | `ns_timeline-icon` |
| `Configuration/RTE/Default.yaml` | RTE preset (not registered in `ext_localconf.php`) |

## Classes

| Class | Role |
|---|---|
| `DataProcessing\DefaultProcessor` | Flattens `pi_flexform` into `content` (section items sorted by `date` or `timeFrom`) |
| `Backend\ItemsProcFunc` | Style list (None, Style 01, Style 02) and Font Awesome icon list |
| `EventListener\NewContentElementPreviewRenderer` | Page module preview from `Templates/Backend/<style>.html` + premium strip |
| `Backend\PremiumStrip` | Strip HTML + trial URL; `''` when the ns_license route is missing |
| `EventListener\PremiumStripPreview` | Strip for timeline elements without preview content; `prependTo()` used by the preview listener |
| `Form\Resolver\PremiumStripFlexFormNoTabsContainerResolver` | Picks the strip container for `pi_flexform` of timeline elements |
| `Form\Container\PremiumStripFlexFormNoTabsContainer` | Core single-sheet FlexForm container + strip |

## Views

- Frontend: `Templates/NsTimeline.html` → `Partials/Standard/Style-1.html` / `Style-2.html` (by
  `normalVariation`).
- Backend: `Templates/Backend/style-1.html` / `style-2.html`.

## Assets

- Frontend: `css/{reset,animate.min,default.min,demo,frst-timeline-style-1,frst-timeline-style-2,font-awesome}.css`,
  `font-awesome/`, `fonts/`, `Icons/`.
- Backend: `css/backend-premium-strip.css`.
