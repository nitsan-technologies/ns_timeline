# Feature — Timeline content element

*Status: Done (free version). Code-read (2026-10-15); content element, edit form and Page module preview seen on
v14.3.7, frontend not tested.*

## Editor flow

1. Add the content element "Timeline" (wizard group `common`, CType `nstimeline`) to a page.
2. Tab "General", FlexForm "Timeline Fields": "Select Standard Variation" (None, Style 01, Style 02; reloads the
   form), "Show Date Opposite", then add timeline items (title, rich text,
   link, date, Font Awesome icon — the icon field is hidden for Style 02).
3. Include the static template "Timeline" or the site set `nitsan/ns-Timeline`.

## Rendering

- `tt_content.nstimeline` (FLUIDTEMPLATE, `templateName = NsTimeline`): `FilesProcessor` (`image`) and
  `DefaultProcessor`, which flattens `pi_flexform` into `content`; section items are sorted by `date` (or
  `timeFrom`) in `newsOrderDirection`, default ascending.
- `NsTimeline.html` renders `Partials/Standard/Style-1.html` or `Style-2.html`; nothing when the variation is
  None. Style 1 prints a year label whenever the year changes between items.
- Theme colour: `lib.enableThemeColor` / `lib.themeColor` from the constants or site settings.

## Settings

| Classic constant | Site set setting |
|---|---|
| `ns_timeline.website.paths.{templateRootPath,partialRootPath,layoutRootPath}` | `ns_timeline.view.*` |
| `ns_timeline.website.settings.{enableThemeColor,themeColor}` | `ns_timeline.configuration.{enableThemeColor,themeColor}` |

The path constants are defined but the setup uses fixed paths (`templateRootPaths.200`,
`partialRootPaths.300`).

## Page module preview

`EventListener\NewContentElementPreviewRenderer` renders `Templates/Backend/<variation>.html` with the
flattened FlexForm as `flexformData` (premium strip on top, see `features/premium-cta.md`). v12/v13 use
`StandaloneView`, v14 the `ViewFactoryInterface`.
