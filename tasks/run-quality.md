# Run quality checks

The repo has no PHPUnit, PHPStan or CS config. Available checks:

```bash
# PHP syntax; also run once with PHP 8.1, the lowest version TYPO3 12 supports
for f in $(find . -name "*.php" -not -path "./.git/*" -not -path "./.Build/*"); do php -l "$f"; done

# composer.json
composer validate --no-check-publish

# XLF and FlexForm well-formedness
for f in Resources/Private/Language/*.xlf Configuration/FlexForms/*.xml; do xmllint --noout "$f"; done
```

## Manual test (per TYPO3 major 12 / 13 / 14)

1. Page module, page with a "Timeline" content element (Style 01 and Style 02): strip once above the timeline
   preview.
2. With ns_revolution_slider installed: its slider elements still show their own preview (listener
   identifiers must not collide).
3. Edit the element, tab "General": strip above "Select Standard Variation". Other content elements' FlexForms
   show no ns_timeline strip (other NITSAN strips may appear for their own plugins).
4. "Start Free Trial" (as system maintainer) opens the ns_license module and its trial modal with
   "Timeline (ns_timeline)" preselected. Cancel; don't start a trial.
5. Deactivate ns_license (test system only): both placements show nothing and the form still saves.
6. Frontend: both styles with several items and dates in different years, with the static template and with
   the site set.
7. Check light and dark backend themes.

Remove test content elements afterwards.
