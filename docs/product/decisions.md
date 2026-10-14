# Product Decisions

Append-only log of product decisions. Each new entry goes at the top.

Format:

## YYYY-MM-DD — Short Decision Title

- **Context:** why the decision was needed.
- **Decision:** what was decided.
- **Rationale:** why this option over the alternatives.
- **Alternatives:** other options considered and why they were not chosen.

---

## 2026-10-04 — Roadmaps Deferred to Last with Defined Scope

- **Context:** Roadmaps were listed in `todo.txt` with "??" — unclear scope. Discussion clarified a significant feature: graph-based learning paths with nodes linking to learnings, shareable via link.
- **Decision:** Defer roadmaps to the very end (after Pomodoro, or separate cycle). Record the intended scope now to avoid re-discovery: personal + share via link (view-only), nodes link to learnings (optional `learning_id`), DAG structure (edges for prerequisites), Vue Flow for graph UI, public read-only view via slug/token.
- **Rationale:** Core tracker (stabilize, filters, groups, masteries, analytics, pomodoro) must land first. Roadmaps are a platform-level feature that needs the foundation solid. Defining scope now prevents scope creep later.
- **Alternatives:** Building roadmaps now (rejected — premature, high complexity); dropping entirely (rejected — valuable for curriculum sharing); building community platform (rejected — turns product into social network).

## 2026-10-04 — Pomodoro Requires Learning Link

- **Context:** Pomodoro is a focus-timer feature. Question was whether sessions can exist without linking to a learning (standalone timer mode).
- **Decision:** Pomodoro sessions **must** link to a learning. No standalone timer mode. Flow: "Start Pomodoro" → pick/create learning → choose preset (25/5, 50/10, custom) → timer starts → on complete, auto-create `LearningSession` with `pomodoro_config` JSON.
- **Rationale:** This is a learning tracker, not a generic productivity timer. Orphan pomodoros create blind spots in skill breakdown, mastery progression, and weekly activity. Required link keeps analytics complete and data model simple (pomodoro = `LearningSession` + config).
- **Alternatives:** Optional link (rejected — invisible time in analytics); default "General Learning" catch-all (rejected — dilutes analytics, users don't recategorize).

## 2026-10-04 — Mastery Is Manual Proficiency Per Skill

- **Context:** Mastery could be auto-calculated from hours, manual user-decided, or hybrid. The existing schema has `Learning.mastery_id` FK and `MasteryDropdown` component.
- **Decision:** Mastery = **manual, user-decided proficiency per skill**. Seed 4 defaults per user (Beginner, Intermediate, Advanced, Expert) with optional rename/add/delete via existing Mastery CRUD. No auto-progression.
- **Rationale:** Self-taught devs know their own competence — hours ≠ mastery. Manual works Day 1 with zero sessions. No threshold tuning (10h = Beginner is arbitrary). Users trust their judgment over opaque formulas. Schema already supports this.
- **Alternatives:** Auto from time (rejected — arbitrary thresholds, wrong for many skills); hybrid with auto-suggest (rejected — adds complexity, can add "suggested" badge later without schema change); fixed global levels (rejected — couples all users, customization needs migration later).

## 2026-10-04 — Groups Is the Only Organization Feature for Now

- **Context:** The schema already contains `groups`, `masteries`, and `tags` (all partially built: `Group`/`Mastery`/`Tag` models exist, `GroupController` and `TagController` are empty stubs, `MasteryController` API and `MasteryDropdown.vue` already work). Building all three risks three overlapping taxonomies.
- **Decision:** Build **Groups** as the sole organization feature in this post-MVP cycle. Defer Masteries UI expansion and Tags. Keep the existing partial backend code for both; do not extend it.
- **Rationale:** For the target persona (self-learners managing multiple skills), broad categories (e.g. Frontend/Backend) are the pressing need. Masteries and tags add schema/UI surface before the basic categorization need is proven.
- **Alternatives:** Building all three (rejected — redundancy and wasted effort); deciding semantics during a discovery task (rejected — the answer is already known); deleting masteries/tags (rejected — `Learning.mastery_id` is wired and `MasteryDropdown` ships, so removal would be churn without value).

## 2026-10-04 — Stabilization Scope: Bugfixes Now, Tests Later

- **Context:** Exploration found functional defects: frontend calls `GET /server/learning-sessions/{id}/activate` while the route is registered as `POST` (`resources/js/stores/session-timer.ts:22`), a `DELETE /server/learning-sessions/{id}` call exists with no registered route, and `app/Models/LearningSession.php:11` imports a non-existent `App\States\LearningSession\CompletedState`. There are also no tests for the session state machine or the `/server/*` endpoints.
- **Decision:** Fix the functional defects as Phase 1 of the post-MVP plan. Defer automated test coverage for the state machine and API endpoints.
- **Rationale:** The defects break real user flows (starting/resuming/deleting sessions) and must be fixed before building features. Test coverage is important but does not block correctness of the fixes themselves.
- **Alternatives:** Full stabilization including tests (rejected for now — slows feature delivery; accepted risk until a later cycle); deferring all fixes (rejected — broken core flows undermine every feature built on top).

## 2026-10-04 — Markdown and Roadmaps Deferred Pending Discovery

- **Context:** `todo.txt` lists "Markdowns??" and "Roadmaps??" with question marks, indicating unclear intent and scope.
- **Decision:** Defer both. Record them as `needs discovery` in the backlog rather than scoping them now.
- **Rationale:** Neither has a defined problem statement. Scoping them prematurely would produce speculative requirements. Core organization and analytics work should land first.
- **Alternatives:** Scoping Markdown as session-note rendering (rejected — assumed requirement); scoping Roadmaps as ordered learning paths (rejected — assumed requirement); dropping them entirely (rejected — they may still be valuable once the core matures).

## 2026-10-04 — MVP Dashboard Excluded from Post-MVP Plan

- **Context:** The MVP Dashboard item in `todo.txt` is unchecked: `DashboardService` is implemented and unit-tested, but three dashboard controllers (`DashboardActivityController`, `DashboardRecentSessionController`, `DashboardSkillBreakdownController`) have empty `__invoke` bodies and `resources/js/pages/Dashboard.vue` renders placeholders.
- **Decision:** Treat the MVP dashboard as a separate, pre-existing track. The post-MVP plan does not include dashboard wiring work, but records it as a hard dependency for the analytics phases.
- **Rationale:** Keeps the post-MVP plan focused on new scope. The dependency is still explicit so period-filter work isn't blocked by surprise.
- **Alternatives:** Including it as Phase 0 of this plan (rejected — user chose to handle it separately); pretending the dependency doesn't exist (rejected — period filters cannot be verified end-to-end without it).

## 2026-10-04 — Target Persona: Self-Taught Developers First

- **Context:** The primary user was undefined, which is the biggest prioritization lever for post-MVP work.
- **Decision:** Primary audience: aspiring developers and self-taught programmers managing multiple skills. Secondary (future): hobbyists (music, art, fitness), students tracking study time, productivity enthusiasts.
- **Rationale:** The current domain (skills, sessions, time analytics) maps directly to the self-learner's core job: *see where my time goes and keep momentum across skills*. Secondary audiences can be served later without schema changes.
- **Alternatives:** Optimizing for a public product launch (rejected — premature; no multi-user, billing, or onboarding requirements exist); generic productivity audience (rejected — dilutes prioritization).