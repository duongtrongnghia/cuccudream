# Phase 6: Visual Rebrand (CSS/Colors/Font)

**Priority:** P1 | **Status:** Pending | **Effort:** 1h  
**Depends on:** Phase 2 (pillar CSS removed), Phase 5 (brand text done)

## Overview
Replace color scheme and font to match Cuc Cu Dream pastel aesthetic.

## Color Palette

| Role | Old | New Hex | New Name |
|------|-----|---------|----------|
| Primary | #2E7D32 (green) | #FF6B6B | Coral Pink |
| Secondary | — | #4ECDC4 | Sky Teal |
| Accent | #2E7D32 | #A78BFA | Lavender |
| Background | #FAF8F5 | #FFF9F0 | Cream |
| Text | #1A1A1A | #2D3436 | Charcoal |
| Muted | #6B6B6B | #636E72 | Warm Gray |

## Font
- **Old**: Geist + Source Serif 4
- **New**: Nunito (rounded, child-friendly)
- Google Fonts import: `https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap`

## File: `resources/css/app.css`

### @theme block changes
```css
--font-sans: 'Nunito', ui-sans-serif, system-ui, sans-serif;

--color-bg-base:       #FFFFFF;
--color-bg-subtle:     #FFF9F0;
--color-bg-muted:      #FFF0E0;
--color-border:        #F0E6D8;
--color-border-strong: #E0D4C4;
--color-text-primary:  #2D3436;
--color-text-secondary:#4A4A4A;
--color-text-muted:    #636E72;
--color-text-inverse:  #FFFFFF;

--color-accent:        #FF6B6B;
--color-accent-light:  #FFE8E8;
--color-accent-dark:   #E05555;
--color-green:         #4ECDC4;
--color-green-light:   #E0FAF7;
--color-salmon:        #A78BFA;
--color-salmon-light:  #EDE9FE;
```

### Body & base
- `background-color: #FFF9F0`
- `color: #2D3436`
- `font-family: 'Nunito', ...`
- Scrollbar track: `#FFF9F0`

### Component color updates
- `.btn-primary`: bg `#FF6B6B`, hover `#E05555`
- `.btn-gold`: bg `#4ECDC4`, hover `#3DB8B0`
- `.input:focus`: border `#FF6B6B`, shadow `rgba(255,107,107,0.12)`
- `.nav-item.active`: bg `#FFE8E8`, color `#E05555`
- `.tab-item.active`: border-bottom `#FF6B6B`
- `.xp-bar-fill` / `.progress-fill`: gradient using `#FF6B6B` to `#A78BFA`
- `.post-card.is-cot`, `.post-card.is-signal`, `.post-card.has-rune`: Remove (already deleted in phase 4)

### Remove
- Font import for Geist and Source Serif 4
- `html.font-serif-mode` rules (no serif mode needed)
- All pillar theme variables (done in phase 2)

## File: `resources/views/layouts/app.blade.php`
- Update any inline color references

## File: `resources/views/layouts/guest.blade.php`
- Update background colors if hardcoded

## Todo
- [ ] P6-1: Replace font import (Geist -> Nunito)
- [ ] P6-2: Update @theme color variables
- [ ] P6-3: Update body/base styles
- [ ] P6-4: Update component colors (btn, input, nav, tab, cards)
- [ ] P6-5: Remove serif-mode CSS rules
- [ ] P6-6: Update layout files if they have inline colors

## Success Criteria
- Page background is cream (#FFF9F0)
- Primary actions are coral pink (#FF6B6B)
- Font is Nunito throughout
- No references to Geist font or old green (#2E7D32) accent in CSS
