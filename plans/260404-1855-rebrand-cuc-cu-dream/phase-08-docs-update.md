# Phase 8: Update CLAUDE.md & Docs

**Priority:** P2 | **Status:** Pending | **Effort:** 30m  
**Depends on:** All other phases

## Overview
Update project documentation to reflect new brand, removed features, and simplified architecture.

## File: `CLAUDE.md`

### Update
- Project purpose: Vietnamese-language children's art & English learning community for parents
- Remove domain concepts: Pillars, Classes, Signals, COT, XP, AIP, Da Khong Cuc, Expedition, Rune
- Keep domain concepts: Topics (admin-managed tags)
- Update model key fields: remove class/xp/aip/level/streak from User, remove pillar/cot/signal/rune from Post
- Remove XpService documentation
- Update CSS conventions: remove pillar/class badges, update color references
- Update route list: remove deleted routes
- Update directory structure: remove deleted files
- Update color scheme references

### Simplify
The CLAUDE.md should reflect a simple Xiaohongshu-style community: post, comment, like, bookmark with topics.

## File: `docs/` directory
- Update any docs that reference old features

## Todo
- [ ] P8-1: Rewrite CLAUDE.md for Cuc Cu Dream
- [ ] P8-2: Update docs/ files if they reference removed features

## Success Criteria
- CLAUDE.md accurately describes current codebase
- No references to removed features in documentation
