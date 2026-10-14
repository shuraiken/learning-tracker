# Accepted User Stories

Accepted stories and their acceptance criteria. Newest at the top.

## Pomodoro Focus Sessions

- **As a:** self-taught developer wanting structured focus time
- **I want to:** run pomodoro sessions (work/break intervals) linked to a specific learning
- **So that:** my focus time feeds into skill analytics and I build a habit of timed deep work.

### Acceptance Criteria

- Given I am on a learning detail or the dashboard / When I click "Start Pomodoro" / Then I must select a learning (existing or create new) and choose a preset (25/5, 50/10, custom work/break minutes) before the timer starts.
- Given a running pomodoro / When a work interval ends / Then a break interval starts automatically (visual + optional browser notification), and the cycle count increments.
- Given a pomodoro completes (target cycles reached or user stops) / Then a `LearningSession` is auto-created with `started_at`, `ended_at`, `total_duration` = sum of work intervals, `status` = completed, and `pomodoro_config` JSON stored (preset name, work_min, break_min, cycles_target, cycles_completed).
- Given I view the skill breakdown / When I filter by period / Then pomodoro sessions contribute to that learning's duration like any other session.
- Given I view a learning's detail / When I check pomodoro history / Then I see a list of completed pomodoro sessions with date, cycles, total focus time.
- Edge cases: user stops mid-cycle → session saved with `cycles_completed`; user changes preset mid-session → not allowed (restart to change); multiple pomodoros for same learning in one day aggregate correctly in analytics.

### Status

- `accepted`

---

## Track Proficiency Per Skill (Mastery)

- **As a:** self-taught developer building a skill portfolio
- **I want to:** set a proficiency level (Beginner/Intermediate/Advanced/Expert) for each learning I'm tracking
- **So that:** I can see at a glance where I stand across skills and filter/group by proficiency.

### Acceptance Criteria

- Given a new user / When they first visit the learnings page / Then 4 default masteries exist for them (Beginner, Intermediate, Advanced, Expert) — seeded on first access or registration.
- Given I am creating or editing a learning / When I open the mastery dropdown / Then I see my masteries (defaults + any I've created) and can select one or create a new mastery inline.
- Given I have assigned masteries to learnings / When I view the learnings index / Then I can filter by mastery level and see the mastery badge on each learning card.
- Given I want to rename a mastery / When I edit it via the mastery management UI / Then the change reflects on all linked learnings immediately.
- Given I delete a mastery that has linked learnings / Then those learnings' `mastery_id` becomes null (soft delete via existing `nullOnDelete` FK), and the mastery is removed.
- Edge cases: mastery name max length; duplicate name validation per user; empty state when no masteries exist (should not happen due to defaults); mastery order in dropdown (custom order or alphabetical).

### Status

- `accepted`

---

## Organize Learnings Into Groups

- **As a:** self-taught developer with many skills
- **I want to:** assign each learning to a group (e.g. Frontend, Backend) and browse learnings by group
- **So that:** I can make sense of a growing skill list and see related skills together.

### Acceptance Criteria

- Given I am on the learnings index / When I create or edit a learning / Then I can optionally select an existing group or create a new one, and the selection persists.
- Given I have learnings assigned to groups / When I view the learnings index / Then I can filter the list by a group and by "no group".
- Given a group with no assigned learnings / When I delete it / Then the group is removed and its learnings become ungrouped (soft-deleted group; `group_id` nulls out via existing `nullOnDelete` FK), and no learnings are deleted.
- Given group name input / When I submit a duplicate group name / Then validation rejects it with a clear error.
- Given another user's groups / When I list groups / Then I only see my own (users own their learnings; confirm ownership model during implementation — `groups` table has no `user_id` column today, see Risks).
- Edge cases: group name max length validation; empty state when no groups exist; filter resets correctly.

### Status

- `accepted`

---

## Filter Skill Breakdown by Time Period

- **As a:** self-taught developer reviewing my dashboard
- **I want to:** filter the skill breakdown by all time, today, this week, or this month
- **So that:** I can see where my time went recently instead of only cumulative totals.

### Acceptance Criteria

- Given the dashboard skill-breakdown panel / When I choose a period (all time / today / this week / this month) / Then the per-skill durations and ordering update to reflect only sessions overlapping that period.
- Given a session spanning a period boundary (started yesterday, still active) / When I filter to today / Then its duration is clipped to time spent inside the period (existing `LearningDurationService` clipping semantics).
- Given "this week" / When I filter / Then the week starts on the configured week start day and is consistent with `weekly-activity` data.
- Given the default view / When the dashboard loads / Then the default period is "all time" (previous behavior preserved).
- Edge cases: zero sessions in the period shows an empty state, not zeros-as-errors; period selection persists across dashboard re-renders within the session; changing period never triggers concurrent requests that render out of order.
- Note: end-to-end verification depends on the MVP dashboard controllers actually returning data (see roadmap dependency).

### Status

- `accepted`

---

## Story

- **As a:** <user>
- **I want to:** <action>
- **So that:** <benefit>

### Acceptance Criteria

- Given <state> / When <action> / Then <outcome>
- Edge cases and details.

### Status

- `proposed` | `accepted` | `done`

---