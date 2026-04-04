# Visual Explanation: The All In Plan Platform Architecture

## Overview

The All In Plan is a Vietnamese-language community platform for marketers and entrepreneurs. Users learn, post content across 5 business pillars, earn XP, level up through 100+ levels, join group expeditions, and compete on leaderboards. The stack is Laravel 12 + Livewire 3 + Alpine.js + Tailwind CSS v4 with SQLite.

## Quick View (ASCII)

```
┌─────────────────────────────────────────────────────────────────────┐
│                        BROWSER (Client)                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────────────┐   │
│  │ Alpine.js │  │ Livewire │  │ Tailwind │  │  Vite (HMR/Build)│   │
│  │  UI State │  │  WebSocket│  │  CSS v4  │  │  Asset Pipeline  │   │
│  └──────────┘  └──────────┘  └──────────┘  └──────────────────┘   │
└──────────────────────────────┬───────────────────────────────────────┘
                               │ HTTP / Livewire Protocol
┌──────────────────────────────▼───────────────────────────────────────┐
│                        LARAVEL 12 (Server)                           │
│                                                                      │
│  ┌─── Routes (web.php) ──────────────────────────────────────────┐  │
│  │  Guest:  /login  /register  /ref/{username}                   │  │
│  │  Auth:   /onboarding  /logout  /membership/expired            │  │
│  │  Main:   /feed  /cot  /tin-hieu  /hoi-dap  /expedition       │  │
│  │          /leaderboard  /@{username}  /hoc-vien  /affiliate    │  │
│  │  Admin:  /admin/topics                                        │  │
│  └───────────────────────────────────────────────────────────────┘  │
│                               │                                      │
│  ┌─── Middleware ─────────────▼──────────────────────────────────┐  │
│  │  auth → RequireActiveMembership → can('admin')                │  │
│  │         ├─ Check class exists (→ onboarding)                  │  │
│  │         ├─ Check banned status (→ logout)                     │  │
│  │         ├─ Check trial/active expiry (→ expired page)         │  │
│  │         └─ Pass through (→ Livewire component)                │  │
│  └───────────────────────────────────────────────────────────────┘  │
│                               │                                      │
│  ┌─── Livewire Components (21) ─────────────────────────────────┐  │
│  │                                                               │  │
│  │  PAGES          SIDEBAR          AUTH          ADMIN          │  │
│  │  ┌──────────┐   ┌──────────┐   ┌─────────┐   ┌──────────┐  │  │
│  │  │ Feed     │   │ MyXp     │   │ Login   │   │ Topics   │  │  │
│  │  │ PostCard │   │ Leader   │   │ Register│   └──────────┘  │  │
│  │  │ Compose  │   │ Challenge│   │ ClassSel│                  │  │
│  │  │ CotPage  │   │ Expedit. │   └─────────┘                  │  │
│  │  │ Signals  │   │ Burning  │                                 │  │
│  │  │ QaPage   │   │ ClassRat │                                 │  │
│  │  │ Expedit. │   └──────────┘                                 │  │
│  │  │ ExpDetail│                                                │  │
│  │  │ Leader   │                                                │  │
│  │  │ Profile  │                                                │  │
│  │  └──────────┘                                                │  │
│  └───────────────────────────────────────────────────────────────┘  │
│                               │                                      │
│  ┌─── Services ───────────────▼──────────────────────────────────┐  │
│  │  XpService.award(user, type, multiplier, desc, reference)     │  │
│  │  ├─ Base XP: post=15, comment=3, cot=20, expedition=25...    │  │
│  │  ├─ Streak multiplier: 7d=1.1x, 30d=1.2x                    │  │
│  │  └─ Level check: XP thresholds (1-60 table, 61+ exponential) │  │
│  └───────────────────────────────────────────────────────────────┘  │
│                               │                                      │
│  ┌─── Models (29) ────────────▼──────────────────────────────────┐  │
│  │                                                               │  │
│  │  CORE           GAMIFICATION      EXPEDITION     ACADEMY      │  │
│  │  ├─ User        ├─ XpTransaction  ├─ Expedition  ├─ Course   │  │
│  │  ├─ Post        ├─ AipTransaction ├─ ExpMember   ├─ Module   │  │
│  │  ├─ Comment     ├─ DaKhongCuc     ├─ ExpCheckin  ├─ Lesson   │  │
│  │  ├─ Like        ├─ DaKhongCucLog                 ├─ Enroll   │  │
│  │  ├─ Bookmark    ├─ PowerSymbol    COMMUNITY      ├─ Progress │  │
│  │  ├─ Topic       ├─ Badge          ├─ Challenge               │  │
│  │  ├─ Membership  ├─ UserBadge      ├─ PillarStat  OTHER      │  │
│  │  ├─ PostAttach  ├─ LeaderSnap     ├─ AffiliateE  ├─ Setting │  │
│  │  ├─ Question                      ├─ Notification            │  │
│  │  └─ Answer                                                    │  │
│  └───────────────────────────────────────────────────────────────┘  │
│                               │                                      │
└──────────────────────────────▼───────────────────────────────────────┘
                               │
                    ┌──────────▼──────────┐
                    │   SQLite Database    │
                    │  database.sqlite     │
                    │  ~25 tables          │
                    └─────────────────────┘
```

## Detailed Flow

### User Journey & Request Lifecycle

```mermaid
flowchart TD
    A["Browser Request"] --> B{"Authenticated?"}
    B -->|No| C["Guest Routes"]
    C --> C1["Login / Register"]
    C1 --> C2["Create User + Trial Membership"]
    C2 --> D["Auth Routes"]

    B -->|Yes| D
    D --> E{"Has Class?"}
    E -->|No| F["Onboarding: ClassSelection"]
    F --> G["Pick 1 of 5 Classes"]
    G --> D

    E -->|Yes| H{"Membership Active?"}
    H -->|Banned| I["Logout + Error"]
    H -->|Expired/Trial Ended| J["Membership Expired Page"]
    H -->|Active| K["Main Platform"]

    K --> K1["Feed / CỐT / Signals / Q&A"]
    K --> K2["Expedition / Detail"]
    K --> K3["Leaderboard"]
    K --> K4["Profile"]
    K --> K5["Admin: Topics"]

    K1 -->|"Create Post"| L["ComposePost"]
    L -->|"Award XP"| M["XpService"]
    M --> N["XP Transaction + Level Check"]

    K2 -->|"Check-in"| O["ExpeditionDetail"]
    O -->|"Award XP"| M

    style A fill:#FEF3C7,stroke:#D4A843,color:#252525
    style K fill:#ECFDF5,stroke:#059669,color:#252525
    style M fill:#EDE9FE,stroke:#7C3AED,color:#252525
    style I fill:#FEE2E2,stroke:#DC2626,color:#252525
```

### Data Model Relationships

```mermaid
erDiagram
    User ||--o{ Post : creates
    User ||--o{ Comment : writes
    User ||--o{ Membership : has
    User ||--o{ XpTransaction : earns
    User ||--o{ ExpeditionMember : joins
    User ||--o{ Bookmark : saves
    User ||--o{ UserBadge : earns
    User ||--o{ CourseEnrollment : enrolls

    Post ||--o{ Comment : has
    Post ||--o{ Like : receives
    Post ||--o{ PostAttachment : has
    Post }o--|| Topic : tagged

    Expedition ||--o{ ExpeditionMember : contains
    Expedition ||--o{ ExpeditionCheckin : tracks

    Course ||--o{ Module : contains
    Module ||--o{ Lesson : contains
    Lesson ||--o{ LessonProgress : tracks

    Badge ||--o{ UserBadge : awarded
```

### XP & Gamification System

```mermaid
flowchart LR
    subgraph Actions
        A1["Post +15"]
        A2["Comment +3"]
        A3["CỐT +20"]
        A4["Expedition +25"]
        A5["Login +2"]
    end

    subgraph Multipliers
        M1["Streak 7d: x1.1"]
        M2["Streak 30d: x1.2"]
        M3["Expedition Class Bonus: x1.2-1.5"]
        M4["Rune First Comment: x2"]
    end

    subgraph Rewards
        R1["XP → Level Up"]
        R2["AIP Currency"]
        R3["Da Khong Cuc Gems"]
        R4["Badges"]
        R5["Power Symbols"]
    end

    Actions --> Multipliers --> R1
    Actions --> R2
    R1 --> L["Level 1-300"]
    L --> J["Job Stage: Tan binh → Empire Builder"]

    style R1 fill:#FEF3C7,stroke:#D4A843,color:#252525
    style R3 fill:#EDE9FE,stroke:#7C3AED,color:#252525
```

### 5 Pillars System

```mermaid
flowchart TD
    subgraph Pillars["5 Business Pillars"]
        P1["Offer 🔥"]
        P2["Traffic ✨"]
        P3["Conversion 🎯"]
        P4["Delivery ⚙️"]
        P5["Continuity 🔗"]
    end

    subgraph Features["Pillar Features"]
        F1["Every Post belongs to 1 Pillar"]
        F2["PillarStat tracks 7-day activity"]
        F3["Burning Zone: hot pillar gets bonus"]
        F4["User Class maps to pillar"]
        F5["PowerSymbol per pillar"]
    end

    P1 --- F1
    P1 --- F4
    Pillars --- F2
    Pillars --- F3
    Pillars --- F5

    style P1 fill:#FEF3C7,stroke:#F59E0B,color:#252525
    style P2 fill:#F3E8FF,stroke:#9333EA,color:#252525
    style P3 fill:#ECFDF5,stroke:#059669,color:#252525
    style P4 fill:#DBEAFE,stroke:#2563EB,color:#252525
    style P5 fill:#FEE2E2,stroke:#DC2626,color:#252525
```

## Key Concepts

1. **Livewire-First Architecture** — No REST API, no SPA. Every page is a Livewire full-page component with server-rendered HTML. Alpine.js handles client-side UI state (dropdowns, timers).

2. **5-Pillar Domain Model** — Every post, course, and class maps to one of 5 business pillars (offer/traffic/conversion/delivery/continuity). This is the fundamental taxonomy of the platform.

3. **XP-Driven Gamification** — XpService is the central business logic hub. All user actions funnel through `award()` which calculates base XP * streak multiplier, logs transactions, and triggers level-up checks.

4. **Membership Gate** — `RequireActiveMembership` middleware is the single checkpoint. All main routes pass through it. It checks: class selected → not banned → membership not expired.

5. **Expedition System** — Group challenges with captains, daily check-ins, class diversity bonuses (5 unique classes = 1.5x XP), and kick mechanics for inactive members.

6. **Content Curation** — Three content tiers: regular posts, Signals (short ≤500 words), and CỐT (curated essentials nominated by level 30+ users).

7. **Rune Mechanic** — Posts can activate a "rune" giving 2x XP to the first commenter within a time window. Creates urgency and engagement.

## Code Example

```php
// XpService — The gamification engine
app(XpService::class)->award(
    $user,                    // Who gets XP
    'expedition_checkin',     // Action type → base 25 XP
    $expedition->getXpBonusMultiplier(), // 1.0-1.5x based on class diversity
    'Check-in: ' . $expedition->title,
    $expedition               // Polymorphic reference for audit trail
);

// Internally:
// 1. Look up base reward: REWARDS['expedition_checkin'] = 25
// 2. Apply streak: user.streak >= 30 ? 1.2x : (>= 7 ? 1.1x : 1.0x)
// 3. Apply multiplier: 25 * 1.2 * 1.5 = 45 XP
// 4. Create XpTransaction record
// 5. Increment user.xp
// 6. Check level-up thresholds
```

## Project Health Summary

```
 Feature Completeness
 ═══════════════════════════════════════════════
 Core (Feed/Post/Comment/Like)     ████████████ 95%
 Auth & Membership                 ██████████░░ 85%
 Expedition System                 ████████░░░░ 70%
 Leaderboard                       ████████░░░░ 70%
 Q&A System                        ████████████ 90%
 Admin (Topics)                    ████████████ 90%
 Academy (Courses)                 ██░░░░░░░░░░ 15%
 Badge System                      ██░░░░░░░░░░ 10%
 Affiliate                         ██░░░░░░░░░░ 10%
 Notifications                     █░░░░░░░░░░░  5%
 ═══════════════════════════════════════════════
 Overall                           ██████░░░░░░ 55%

 Models: 29 total | 8 were empty (now implemented)
 Tests:  0 real tests | Only placeholders
 Stack:  Laravel 12 · Livewire 3 · Tailwind v4 · SQLite
```
