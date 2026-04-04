# The All In Plan — Comprehensive Implementation Plan

## Status: READY
## Priority: HIGH
## Estimated Phases: 7
## Related: [Academy Structured Learning](../260401-1322-academy-structured-learning/plan.md)

## Current State
- 24/24 Livewire components render OK
- 10/10 core user actions work (post, like, comment, bookmark, expedition, course, Q&A, XP, leaderboard, membership)
- 19 features fully working, 14 features not implemented

## Phase Overview

| Phase | Description | Priority | Effort | Status |
|-------|-------------|----------|--------|--------|
| 01 | Badge system + seeding | HIGH | Medium | pending |
| 02 | Notification system (dispatch + display) | HIGH | Medium | pending |
| 03 | CỐT nomination + Expedition lifecycle | HIGH | Medium | pending |
| 04 | Scheduled jobs (leaderboard snapshots, pillar stats, challenge progress, expedition kicks) | MEDIUM | Medium | pending |
| 05 | Gamification wiring (DaKhongCuc, PowerSymbol, AIP) | MEDIUM | Medium | pending |
| 06 | Search + Admin panel expansion | MEDIUM | Medium | pending |
| 07 | Test suite (unit + feature tests) | HIGH | Large | pending |

## Dependencies
- Phase 02 should come before Phase 03 (notifications dispatched by CỐT/Expedition events)
- Phase 04 depends on Phase 03 (expedition lifecycle needed for kick/complete jobs)
- Phase 05 is independent
- Phase 06 is independent
- Phase 07 runs last (tests all implemented features)

## Execution Order
1. **Academy Structured Learning** plan (4 phases) — database, backend, frontend, admin
2. **This plan** phases 01→07 sequentially

## Cook Commands
```
/ck:cook plans/260401-1322-academy-structured-learning/plan.md --auto
/ck:cook plans/260401-1258-comprehensive-implementation/plan.md --auto
```
