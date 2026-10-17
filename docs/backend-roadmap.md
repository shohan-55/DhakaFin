# Gradual backend development

The Laravel conversion is Phase 1. Existing feature notices remain intact until the corresponding backend is implemented and tested.

| Order | Feature | Work before activation |
| --- | --- | --- |
| 1 | Contact / consultation requests | Validation, consent, spam/rate limits, database storage, verified mail delivery and honest success/failure states |
| 2 | Admin access and content management | Authentication, authorization, secure admin provisioning, audit logs; edit services, team and articles |
| 3 | Notices, deadlines and knowledge library | Reviewed sources, publishing workflow, document management and search |
| 4 | Tax/VAT calculators | Verified rules by assessment year, reviewed examples, regression tests and limitations |
| 5 | Client account and document area | Account recovery, access isolation, protected uploads, authorization tests and retention |
| 6 | Advanced tax research / AI | Source retrieval, citations, privacy, cost controls and clearly stated limitations |

The ordering is a proposed development sequence. Each feature should be implemented on its own branch, tested, reviewed, then activated. Keep its Coming soon notice until activation. Do not remove `PENDING` entries in `public/assets/production.js` merely because the project now runs on Laravel.

Extract a page's relevant verbatim section when introducing dynamic Blade content. Use named routes, Form Requests, policies and controllers as each feature needs them. Keep existing visual markup and responsive behavior, and update preservation assertions deliberately for that feature. Avoid introducing a new CSS framework or starter-kit layout that restyles the site.
