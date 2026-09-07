# Writing User Stories

## Purpose

Turn a vague request into a crisp, testable unit of work: a user story with acceptance criteria.

---

## Transformation Guide

Clarify before writing:

* Who is the user?
* What is the underlying goal?
* Why do they need it now?
* What is the smallest slice that delivers value?

---

## Story Format

As a `<type of user>`, I want to `<action>`, so that `<benefit>`.

Keep each story focused on a single capability. Split large requests into several stories.

---

## Acceptance Criteria

Write acceptance criteria as testable, Given/When/Then statements when possible.

Format:

Given a starting state.

When an action is taken.

Then an observable outcome.

Include concrete details (thresholds, roles, data, states) so `done` is unambiguous.

---

## Definition of Done

A story is done when:

* Acceptance criteria are met.
* Edge cases are handled.
* It works in the real product environment.
* It does not regress existing behavior.

---

## Anti-patterns

* Vague value words ("fast", "smooth") without a measurable target.
* Multiple features crammed into one story.
* Prescribing a specific implementation instead of a behavior.
* Acceptance criteria that cannot be tested.
* Scope creep beyond the stated outcome.

---

## Examples

Vague request:

"Make it easier to see progress."

Good stories break this into measurable slices tied to the learning-tracker app, each with its own acceptance criteria (e.g., a dashboard that shows the percentage of a skill's session completed this week, with the number derived from logged session timestamps).