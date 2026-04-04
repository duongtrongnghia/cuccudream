---
title: "Rebrand to Cuc Cu Dream"
description: "Rebrand Laravel app from The All In Plan (marketer community) to Cuc Cu Dream (children's art & English learning)"
status: pending
priority: P1
effort: 6h
branch: main
tags: [rebrand, css, cleanup]
created: 2026-04-04
---

# Rebrand: The All In Plan -> Cuc Cu Dream

## Phases

| # | Phase | Status | Effort | Files |
|---|-------|--------|--------|-------|
| 1 | [Config & Environment](phase-01-config-env.md) | Pending | 15m | 3 files |
| 2 | [Remove Pillar System](phase-02-remove-pillars.md) | Pending | 1h | ~15 files |
| 3 | [Remove Class System](phase-03-remove-classes.md) | Pending | 45m | ~10 files |
| 4 | [Remove Gamification Features](phase-04-remove-gamification.md) | Pending | 1h | ~20 files |
| 5 | [Brand Name Replacement](phase-05-brand-name.md) | Pending | 30m | ~26 files |
| 6 | [Visual Rebrand (CSS/Colors/Font)](phase-06-visual-rebrand.md) | Pending | 1h | 3 files |
| 7 | [Route & Navigation Cleanup](phase-07-routes-cleanup.md) | Pending | 30m | ~5 files |
| 8 | [Update CLAUDE.md & Docs](phase-08-docs-update.md) | Pending | 30m | 2 files |

## Dependency Graph

```
Phase 1 (config) ─┐
Phase 2 (pillars) ─┼─> Phase 5 (brand names) ─> Phase 6 (CSS) ─> Phase 8 (docs)
Phase 3 (classes) ─┤
Phase 4 (gamify)  ─┘─> Phase 7 (routes)
```

Phases 1-4 are independent, can run in parallel. Phase 5-7 depend on cleanup being done first. Phase 8 last.

## Rollback

Git branch before starting. Each phase is a separate commit. Revert any commit independently.

## Key Risk

- **Blade views reference removed features**: Grep thoroughly before deleting models/components. Medium likelihood, low impact (runtime error caught immediately).
- **Migration ordering**: New migration to drop columns must run after existing ones. Low risk.
