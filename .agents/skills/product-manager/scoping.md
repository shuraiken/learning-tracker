# Scoping

## Purpose

Turn the highest-priority work into a concrete plan: tasks, in/out of scope, estimates, and risks.

---

## Steps

1. Restate the goal the work serves.
2. Break an epic or large story into concrete tasks.
3. Determine what is in scope and out of scope.
4. Estimate effort, flagging assumptions.
5. Surface risks, unknowns, and dependencies before starting.

---

## In Scope / Out of Scope

Explicitly list both.

* In scope — what will be delivered in this effort.
* Out of scope — what is intentionally deferred, and why.

Deferring is a decision; record it in `docs/product/decisions.md`.

---

## Estimation

Estimates are predictions, not promises.

* Break work small enough to reason about size.
* Identify unknowns and communicate them early.
* State the unit (points, hours, complexity) explicitly.
* Note effort by the actual tech stack (this is a Laravel + Vue/JS application; account for backend, frontend, database/migrations, and tests).

---

## Risk Savvy

Ask and answer:

* What could fail?
* What are the unknowns?
* What does this depend on?
* Does it touch shared state, auth, or the data model?

Communicate risks early rather than discovering them mid-build.

---

## Output

Returns:

* Broken-down tasks.
* In/out of scope.
* Estimates.
* Risks and dependencies.

A preceded scoping decision is recorded back into the product memory.