# UI Guidelines — For AI Agents (Strict)

> **Enforcement: STRICT.** Follow exactly. If you must deviate, explain why in the PR description. Never hardcode colors, fonts, or spacing. Use tokens + `@/components/ui/*` only.
> **Stack:** Laravel + Inertia + Vue 3 + Tailwind CSS v4 + shadcn-vue (`default / neutral / cssVariables`) + `lucide-vue-next` + `reka-ui`.
> **Sources of truth:** `resources/css/app.css` (tokens), `components.json` (component system), `resources/js/layouts/*` (shell).

## 1. Color Scheme

Base is **neutral monochrome**. Brand accent is **Emerald B** (growth/progress). All colors via CSS vars → Tailwind utilities. No hex.

### 1.1 Base tokens (lock — do not change)

Sourced from `resources/css/app.css:87-158`:

| Role                       | Light                               | Dark                                 | Use as                                    |
| -------------------------- | ----------------------------------- | ------------------------------------ | ----------------------------------------- |
| `background / foreground`  | `hsl(0 0% 100%) / hsl(0 0% 3.9%)`   | `hsl(0 0% 3.9%) / hsl(0 0% 98%)`     | `bg-background text-foreground` on `body` |
| `card / card-foreground`   | white / near-black                  | near-black / near-white              | `bg-card` for panels                      |
| `muted / muted-foreground` | `hsl(0 0% 96.1%) / hsl(0 0% 45.1%)` | `hsl(0 0% 16.08%) / hsl(0 0% 63.9%)` | secondary text: `text-muted-foreground`   |
| `border / input / ring`    | `hsl(0 0% 92.8%) / 89.8% / 3.9%`    | `hsl(0 0% 14.9%) / 14.9% / 83.1%`    | `border-border`, `outline-ring/50`        |
| `destructive`              | `hsl(0 84.2% 60.2%)`                | `hsl(0 84% 60%)`                     | delete only: `bg-destructive`             |
| `sidebar-background`       | `hsl(0 0% 98%)`                     | `hsl(0 0% 7%)`                       | sidebar only                              |

### 1.2 Brand accent B — Emerald (proposed, swappable)

| Token                | Light              | Dark               | Utility                 |
| -------------------- | ------------------ | ------------------ | ----------------------- |
| `--brand`            | `hsl(160 84% 39%)` | `hsl(160 60% 45%)` | `bg-brand border-brand` |
| `--brand-foreground` | `hsl(0 0% 98%)`    | `hsl(0 0% 9%)`     | `text-brand-foreground` |

**Use brand ONLY for:** primary CTA (1 per view), active nav item, progress/mastery indicators, focus-active states.
**Never use brand for:** body text, large backgrounds, destructive actions, informational notes.

Semantic reserve: warning = `--chart-4` amber, info = `--chart-1` blue, success = brand. Charts use `--chart-1`–`--chart-5` in order.

### 1.3 Rules

- DO: `bg-primary text-primary-foreground`, `bg-muted text-muted-foreground`, `bg-card border-border`, `bg-brand text-brand-foreground`.
- DON'T: `bg-[#10B981]`, `bg-emerald-500`, `text-black`, `bg-white`, `text-gray-500`. These break dark mode.
- Dark mode: must render in both. Test by toggling `.dark`. Only vars adapt automatically.
- Accessibility: never color-alone. Pair with `lucide` icon + label. Contrast ≥ 4.5:1 for text.
- Opacity: use `/50`, `/80` modifiers on tokens (e.g. `outline-ring/50`), not new colors.

### 1.4 How to swap accent later

This accent may change. To change: 1) edit 2 lines (`--brand` light/dark) in `app.css`, 2) update table above, 3) no component edits needed because AI only used `bg-brand`. Do not reintroduce hardcoded emerald utilities.

## 2. Design Language

Calm, minimal learning-tool. `shadcn-vue default`, radius `--radius: 0.5rem` (`--radius-lg: var(--radius)`, `-md: -2px`, `-sm: -4px`).

- **Components first:** use `@/components/ui/*` (button, card, dialog, input, dropdown, badge) built on `reka-ui` + `class-variance-authority` + `tailwind-merge`. No hand-rolled buttons/cards/modals.
- **Icons:** `lucide-vue-next` only. No emoji as UI icons.
- **Shape:** rounded cards `rounded-lg border-border bg-card`, popovers `bg-popover`, inputs `border-input`.
- **States (mandatory):** `hover:` subtle, `focus-visible:ring-ring`, `disabled:opacity-50 pointer-events-none`, loading skeleton, empty state via `PlaceholderPattern.vue`, destructive confirm via dialog + `bg-destructive`.
- **Motion:** `tw-animate-css` only, subtle (fade/slide). No custom keyframes.
- **Existing helpers:** `flex-center` (`flex items-center justify-center`), base `* { @apply border-border outline-ring/50; }` — reuse, don't duplicate.

DON'T: introduce new design systems, custom shadows, gradient backgrounds, or additional icon libs.

## 3. Typography

Family: **`Instrument Sans` only**, wired as `--font-sans` in `app.css:11`. Rendered via `font-sans` (default on `body`).

| Level         | Component / Utility                        | Use                      |
| ------------- | ------------------------------------------ | ------------------------ |
| Page title    | `Heading.vue` (`text-2xl font-semibold`)   | 1 per page               |
| Section title | `HeadingSmall.vue` (`text-lg font-medium`) | card/section headers     |
| Body          | `text-sm text-foreground`                  | default                  |
| Secondary     | `text-sm text-muted-foreground`            | descriptions, timestamps |
| Caption       | `text-xs text-muted-foreground`            | hints, counts            |
| Links         | `TextLink.vue`                             | inline links only        |

Rules: no new webfonts without approval. No arbitrary `font-[...]`, `text-[13px]`. Truncate long skill names with `truncate`. Errors via `InputError.vue`, never raw red text.

## 4. Layout

Shell-driven. Pick shell, don't build chrome.

- **Authenticated:** `layouts/app/AppSidebarLayout.vue` via `AppLayout.vue` (`:breadcrumbs`). Contains `AppSidebar.vue` + `AppHeader.vue` + `AppSidebarHeader.vue` (mobile) + `AppContent.vue`.
- **Guest:** `AuthLayout.vue` (centered card, no sidebar).
- **Settings:** `layouts/settings/*` + `AppearanceTabs.vue` for appearance.
- **Container:** all page content inside `AppContent.vue`. Standard rhythm: `space-y-6` page, `gap-4` grids, `p-4 md:p-6` cards.
- **Nav:** `NavMain.vue` (primary), `NavFooter.vue`, `NavUser.vue` + `UserMenuContent.vue`. Active item uses brand. Breadcrumbs via `Breadcrumbs.vue` on every sub-page (`learnings/*`, `settings/*`).
- **Dashboard pattern:** grid `grid gap-4 md:grid-cols-2 lg:grid-cols-3` of `Card`, chart then recent list. See `pages/Dashboard.vue`.
- **Forms:** stacked `space-y-4`, label + input + `InputError`. Primary CTA left/bottom, destructive right with confirm.
- **Responsive:** mobile-first. Sidebar collapses (use `AppSidebarHeader.vue` trigger). No fixed `w-[...px]` page widths. Tables → cards on mobile.

DON'T: create new layouts, bypass `AppContent`, hardcode max-widths, or put >1 primary CTA per view.

## 5. AI Checklist (copy into PR)

- [ ] No hex / `emerald-*` / `gray-*` / `black` / `white` utilities — tokens only?
- [ ] Uses `ui/*` + `lucide` + `reka-ui` — no custom primitives?
- [ ] `Instrument Sans` only, `Heading*` + `text-sm/xs` scale?
- [ ] Correct shell (`AppLayout` vs `AuthLayout`), breadcrumbs, `AppContent`?
- [ ] Light + dark verified, focus/disabled/empty states handled?
- [ ] If brand changed, only `--brand` tokens touched?
