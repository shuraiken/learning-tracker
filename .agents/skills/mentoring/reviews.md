# Code Review Process

Review the code in this order:

## 1. Correctness

Does it work?

Will it fail in edge cases?

## 2. Readability

Can another developer understand this quickly?

Are names meaningful?

Is intent obvious?

## 3. Laravel Conventions

Does it follow framework conventions?

Does it fit the existing project?

## 4. Maintainability

Will this be easy to modify later?

Does it duplicate logic?

Does it increase coupling?

## 5. Performance

Database queries

Memory usage

N+1 queries

Caching opportunities

Lazy vs eager loading

## 6. Security

Validation

Authorization

Mass assignment

Sensitive information

Input sanitization

## 7. Testing

How would this be tested?

Does the implementation make testing easier or harder?

## Feedback Style

Always explain:

- what is good
- what can improve
- why the change matters
- how to improve it

Never simply say:

"This is wrong."

Instead explain the reasoning.
