# Product Backlog

Groomed, prioritized product work. States: `ready` | `in-progress` | `done`.

Order items by priority (highest first). Add an estimate and the framework/score when used.

Estimates are T-shirt sizes (XS/S/M/L) covering backend + frontend + tests. Priorities are P0 (must) → P3 (could).

## Ready

| Item | Priority / Score | Estimate | Notes |
| ---- | ---------------- | -------- | ----- |
| Fix session flow defects: activate `GET`/`POST` mismatch, missing `DELETE` session route, dead `CompletedState` import | P0 | XS | `session-timer.ts:22` vs route; `LearningSession.php:11` (decisions.md: Stabilization Scope) |
| Skill breakdown period filters (all time / today / this week / this month) | P0 | S | Period clipping already in `LearningDurationService` (tested); needs controller param + UI; E2E blocked on MVP dashboard wiring |
| Groups backend: controller, service, validation, tests | P1 | M | `group_id` FK and `Group` model exist; `GroupController` is an empty stub |
| Groups frontend: assign learning to group, group filter/browse | P1 | M | Depends on Groups backend; touches `learnings/Index.vue` + learning form |
| Masteries UI: assign mastery to learning, inline create/rename, seed 4 defaults per user | P1 | S | API CRUD + `MasteryDropdown` exist; needs default seeder + UI polish |
| Improved dashboard analytics: trends, per-skill trends, streaks, period comparison | P2 | L | Depends on period filters; builds on `DashboardService` |
| Pomodoro mode: required learning link, presets (25/5, 50/10, custom), auto-create LearningSession with `pomodoro_config` JSON, goal counter, per-learning history | P3 | M | Session timer enhancement; no schema change (JSON column on `LearningSession`) |

## In Progress

| Item | Estimate | Notes |
| ---- | -------- | ----- |

## Done

| Item | Done On | Notes |
| ---- | ------- | ----- |

## Deferred (needs discovery or intentionally postponed)

| Item | Priority / Score | Estimate | Notes |
| ---- | ---------------- | -------- | ----- |
| Tags: model wiring, CRUD, filtering | deferred | M | `Tag`/`LearningTag` are empty stubs; Groups-only decision (decisions.md 2026-10-04) |
| Markdown rendering for notes/descriptions | deferred | needs discovery | No defined problem statement (decisions.md 2026-10-04) |
| Roadmaps / ordered learning paths | deferred | needs discovery | Scope defined for future: personal + share via link, nodes→learnings, DAG, Vue Flow (decisions.md 2026-10-04) |
| Automated tests: session state machine, `/server/*` endpoints, route ownership | deferred | M | Accepted risk for now (decisions.md: Stabilization Scope) |

---

_Backlog seeded 2026-10-04 from the post-MVP planning session. See roadmap.md for phase order._