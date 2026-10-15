# AGENTS.md — ns_timeline (TYPO3 Timeline & Events)

Agent router for the Timeline free extension. Load only what your task needs.

> **Principles:** `context/principles.md`
> **Session log:** `context/session-state.md`
> **Doc index:** `context/docs-map.md`

---

## Always load

| File | Why |
|---|---|
| `context/principles.md` | Guardrails, "nothing that works may break" rule |
| `context/core.md` | Identity, TYPO3/PHP/ns_license constraints, tooling, known issues |

---

## Task router

| Task | Load |
|---|---|
| Timeline content element: FlexForm, styles, data processor, TypoScript / site set, Page module preview | `context/features/timeline-element.md` |
| Premium strip / free trial / ns_license dependency | `context/features/premium-cta.md` |
| Files, classes, TypoScript structure, assets | `context/architecture.md` |
| Lint / validate / manual test | `tasks/run-quality.md` |
| Update agent context after a change | `tasks/context-update.md` |

---

## Feature status

| Feature | Status | Agent entry |
|---|---|---|
| Timeline content element with two styles and repeatable items | Done (code-read; edit form and preview seen on v14.3.7) | `context/features/timeline-element.md` |
| Premium strip in the Page module preview and the element's FlexForm + ns_license free-trial deep link | Done (needs ns_license 14.5.4 release) | `context/features/premium-cta.md` |

---

## Quick commands

```bash
cd packages/ns_timeline            # or typo3conf/ext/ns_timeline in Classic mode
for f in $(find . -name "*.php" -not -path "./.git/*"); do php -l "$f"; done
composer validate --no-check-publish
for f in Resources/Private/Language/*.xlf Configuration/FlexForms/*.xml; do xmllint --noout "$f"; done
```
