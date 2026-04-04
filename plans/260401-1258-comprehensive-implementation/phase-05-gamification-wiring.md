# Phase 05: Gamification Wiring (DaKhongCuc, PowerSymbol, AIP)

## Priority: MEDIUM
## Status: pending

## Overview
Wire the remaining gamification mechanics: Đá Không Cực gem awarding, Power Symbol fragment accumulation, AIP earn/spend.

## Key Files
- `app/Services/DaKhongCucService.php` (CREATE)
- `app/Services/PowerSymbolService.php` (CREATE)
- `app/Services/AipService.php` (CREATE)
- `app/Services/XpService.php` (MODIFY — call badge/gem checks after award)
- `app/Livewire/ProfilePage.php` (MODIFY — show power symbols properly)

## Implementation Steps

1. **DaKhongCucService**:
   - `award(User, int delta, string reason, ?User awardedBy)`: increment `da_khong_cuc.total_count`, create log entry
   - Trigger conditions: Level 100 reached (1 gem), Expedition captain complete (1 gem), Admin manual award
   - Admin-only manual award via admin panel

2. **PowerSymbolService**:
   - `addFragments(User, string pillar, int fragments)`: increment fragments, check level-up threshold
   - Earn fragments: post in pillar (+1), CỐT in pillar (+3), expedition in pillar area (+2)
   - Level thresholds: Lv1=10, Lv2=30, Lv3=60, Lv4=100 fragments

3. **AipService**:
   - `earn(User, int amount, string reason, ?Model ref)`: add to user.aip, create aip_transaction(type=earn)
   - `spend(User, int amount, string reason, ?Model ref)`: subtract, create aip_transaction(type=spend), throw if insufficient
   - Earn triggers: expedition complete, course complete, community challenge
   - Spend triggers: expedition chaos deposit, future marketplace

4. Integrate into XpService.award() — after XP, also call PowerSymbolService based on pillar

## Success Criteria
- [ ] Gems awarded on level 100 + expedition captain complete
- [ ] Power symbol fragments accumulate per pillar
- [ ] AIP earn/spend with transaction logging
- [ ] Profile shows power symbol levels correctly
