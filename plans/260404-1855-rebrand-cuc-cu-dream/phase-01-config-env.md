# Phase 1: Config & Environment

**Priority:** P1 | **Status:** Pending | **Effort:** 15m

## Overview
Update environment config and deploy script to reflect new brand/database.

## Files to Modify

### `.env.example`
- `APP_NAME="Cúc Cu Dream"`
- `DB_DATABASE=cuc_cu_dream`

### `config/app.php`
- Verify `'name' => env('APP_NAME', 'Cúc Cu Dream')` — update default fallback

### `deploy.sh`
- Update domain/paths references from old brand to new

## Todo
- [ ] P1-1: Update `.env.example` with new APP_NAME and DB_DATABASE
- [ ] P1-2: Update `config/app.php` default app name
- [ ] P1-3: Update `deploy.sh` domain/paths

## Success Criteria
- `config('app.name')` returns "Cúc Cu Dream"
- Database name is `cuc_cu_dream`
