# DhakaFin – Grouped Corporate Navigation Update

Updated the previously released **DhakaFin Live All Features with Coming Soon** cPanel archive.

## Navigation
- Expertise ▾ — accounting, tax, audit, VAT, virtual CFO, business advisory
- Digital Tools ▾ — calculators, Mushak, deadlines
- Tax & Insights ▾ — law reader, research, circulars, rates, articles
- Company ▾ — about, team, audiences, consultation, contact
- Right-hand actions: Client Services and Let’s Talk.

Desktop supports hover, click, Escape, and click-outside dismissal. Mobile/tablet uses an accessible accordion navigation. The original sticky capsule effect is preserved.

## Deployment
The archive is **document-root-ready**. Back up the existing cPanel directory first, then upload the ZIP into the document root and extract. `index.html` must remain at the document root, with `pages/` and `assets/` alongside it. Clear browser/CDN cache if old nav persists.

## Scope
No Laravel/backend work was performed. All existing public pages and links remain. Incomplete digital tools continue using the existing Coming Soon modal.
