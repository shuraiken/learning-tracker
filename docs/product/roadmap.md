# Product Roadmap

Goals, current focus, and proposed next steps.

## Current Goal

Deliver the post-MVP value loop for self-taught developers: **reliable session tracking → visible time analytics → organized skills → proficiency tracking → focus sessions** — so users can see where their learning time goes, stay organized across multiple skills, track proficiency, and run structured focus sessions.

## Current Focus

Phase 1 — Stabilize: fix the known session-flow defects before building new features.

## Next Steps

Ordered, concrete next actions.

1. **Phase 1 — Stabilize (XS).** Fix the `GET`/`POST` activate-route mismatch, register the missing `DELETE /server/learning-sessions/{id}` route (or remove the frontend call), and remove the dead `CompletedState` import from `app/Models/LearningSession.php`.
2. **Phase 2 — Period filters (S).** All time / today / this week / this month filters on the skill-breakdown panel. Depends on `LearningDurationService` period clipping (already implemented and tested); blocked for E2E verification until the MVP dashboard wiring is done (see decision 2026-10-04).
3. **Phase 3 — Groups epic (M + M).** 3a: backend — `GroupController`, service, validation, tests. 3b: frontend — assign a learning to a group, group filter/browse in the learnings list.
4. **Phase 4 — Masteries UI (S).** Assign mastery to learning (existing `MasteryDropdown`), inline create/rename masteries, seed 4 defaults per user (Beginner/Intermediate/Advanced/Expert). API CRUD already exists.
5. **Phase 5 — Improved dashboard analytics (L).** Deeper analytics beyond the MVP overview: trends over time, per-skill trends, streaks, period-over-period comparison. Depends on Phase 2.
6. **Phase 6 — Pomodoro mode (M).** Required learning link. Presets: 25/5, 50/10, custom. Auto-create `LearningSession` with `pomodoro_config` JSON on completion. Goal counter (target pomodoros). Per-learning pomodoro history.

Estimates are T-shirt sizes (XS/S/M/L), relative, covering backend + frontend + tests for each item.

## Deferred

Work intentionally deferred and the reason (tie to a decision in decisions.md).

| Item | Reason |
| ---- | ------ |
| MVP dashboard wiring (3 empty controllers, placeholder `Dashboard.vue`) | Handled as a separate pre-existing track; post-MVP plan excludes it (decisions.md 2026-10-04 — MVP Dashboard Excluded) |
| Tags | Groups-only decision (decisions.md 2026-10-04 — Groups Is the Only Organization Feature); `Tag` model remains an empty stub |
| Markdown notes | No defined problem statement; needs discovery (decisions.md 2026-10-04 — Markdown and Roadmaps Deferred) |
| Roadmaps / learning paths | Deferred to last; scope defined (personal + share via link, nodes link to learnings, DAG, Vue Flow) — decisions.md 2026-10-04 |
| Automated tests for session state machine + `/server/*` endpoints | Bugfix-only stabilization accepted as risk (decisions.md 2026-10-04 — Stabilization Scope) |