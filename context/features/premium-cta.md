# Feature — Premium strip and ns_license free trial

*Status: Done. Verified live on TYPO3 14.3.7 (2026-10-15): strip above the timeline preview in the Page module
and above the FlexForm in the edit form (tab "General"); the button opens the ns_license module with
"Timeline (ns_timeline)" preselected in the trial modal. Requires ns_license 14.5.4 (deep-link support) to be
released. v12/v13 not tested.*

## Licensing status

Freemium. Premium product (T3Planet catalog): "Timeline", €129, https://t3planet.de/en/typo3-timeline-extension
(not linked from the UI; the trial stays inside the TYPO3 backend). Catalog features: 17+ timeline designs,
horizontal and vertical layouts, news/blog integration, 45+ animations, custom fonts and colours, ascending or
descending order, image and video support.

## Dependency

`composer.json` `"nitsan/ns-license": "^14.5.4"`, `ext_emconf.php` `'ns_license' => '14.5.4-14.9.99'`. Raises
the effective minimum to TYPO3 12.4.18 (see `core.md`).

## Placements

| Place | Position |
|---|---|
| Page module, preview of a Timeline content element | Below the element header, above the timeline preview |
| Content element edit form, tab "General" | Above "Select Standard Variation" (inside the `pi_flexform` panel) |

## How it works

- `Backend\PremiumStrip::render()`: `actions-rocket` icon, headline and description on two lines,
  `btn btn-primary` "Start Free Trial →" (the arrow is `aria-hidden` markup). Link = route
  `nitsan_nslicensemodule` + `trial=ns_timeline` + `#free-section`, same frame. Returns `''` without
  ns_license, so neither placement shows anything.
- Preview: `PageContentPreviewRenderingEvent` stops propagation as soon as a listener sets preview content.
  ns_timeline's own `NewContentElementPreviewRenderer` sets it for every timeline element with a FlexForm, so it
  calls `EventListener\PremiumStripPreview::prependTo()` itself, which prepends the strip and adds the
  stylesheet through `PageRenderer`. `PremiumStripPreview` is also a listener (ordered after
  `ns-timeline/flexform-process`) and only runs for timeline elements whose `pi_flexform` is empty. The record
  type comes from `getRecordType()` (v13+) or the row's `CType` (v12).
- Edit form: `ext_localconf.php` registers `Form\Resolver\PremiumStripFlexFormNoTabsContainerResolver` for node
  `flexFormNoTabsContainer`, priority **48** (taken: ns_news_comments 40, ns_event 41, ns_gallery 42,
  ns_helpdesk 43, ns_social_login 44, ns_faq 45, ns_open_streetmap 46, ns_comments 47; duplicates make core
  throw). It matches `tt_content.pi_flexform` of CType `nstimeline`, not inline children, and returns
  `Form\Container\PremiumStripFlexFormNoTabsContainer`, which prepends the strip and adds the stylesheet. If the
  FlexForm ever gets a second sheet, core switches to `flexFormTabsContainer` and the strip disappears from the
  form.
- DI: `Services.yaml` has no `Classes/*` sweep. `Backend\PremiumStrip` and `Form\*` are registered explicitly;
  without the `Form\*` entry the container is created without `NodeFactory` and the edit form throws
  ("`$nodeFactory` must not be accessed before initialization", seen live on 14.3.7).

## Strip

- Labels in `locallang.xlf`: `premium.strip.headline` "You're using the free version of NS Timeline",
  `premium.strip.description` "Unlock premium features and get more out of NS Timeline.",
  `premium.strip.trial` "Start Free Trial".
- `Resources/Public/css/backend-premium-strip.css`: `.ns-timeline-premium-strip` — team-wide design (light-blue
  card `#e7f2fc`, border `#8eb6e8`, 4px `#2b6cb0` left accent, 6px radius, padding 14px 18px, `light-dark()`
  for dark mode, button `#007bff` / hover `#0062cc`); inset 16px inside the FlexForm panel.
- Measured on v14.3.7: 849×67px in the edit form (883px panel); 482×119px in the Page module preview, where the
  button wraps below the text.
- ns_license's module requires system maintainer access; other users get access denied after clicking.
