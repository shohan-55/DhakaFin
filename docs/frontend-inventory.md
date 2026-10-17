# DhakaFin frontend inventory — 17 October 2026

## Confirmed pages
- `index.html` — homepage, embedded CSS and JS.
- `pages/tools.html` — tool directory.
- `pages/personal-tax.html`, `pages/corporate-tax.html` — tax landing pages.
- `pages/tds-calculator.html`, `pages/vat-calculator.html` — calculator presentation pages.
- `pages/ai-tax-search.html` — planned legal search.
- `pages/tax-deadlines.html` — deadlines presentation.
- `pages/consultation.html` — consultation enquiry.
- `pages/privacy.html` — privacy copy.

## Confirmed assets
- `assets/site.css`, `assets/site.js`
- `assets/signature.js`
- `assets/motion.js`
- `assets/grouped-nav.js`
- `assets/production.js`

## Observed behavior
- Contact form in homepage opens a `mailto:` draft; no verified backend delivery.
- `production.js` intercepts demo/pending tool submissions.
- Homepage includes illustrative dashboard figures and a sample workspace mode.
- Navigation JS supports responsive menus and Escape-key closing.
- Some tool pages specify `noindex, follow`; decide intentional indexing policy before launch.

## Conversion contract
1. No CSS restyling, typography, spacing, animation or interaction redesign.
2. Preserve old `/pages/*.html` links during transition or redirect them deliberately.
3. Preserve all relative asset paths and JS initialization.
4. Preserve disabled/pending behavior until each calculator or AI feature is independently tested.
5. Laravel app stays outside public web root; serve only Laravel `public`.
6. Do not merge or deploy without testing and owner approval.

## Not yet verified
- Complete recursive file list and all linked assets.
- Desktop/mobile screenshot parity.
- Accessibility and link integrity across all pages.
- Tax-law calculation accuracy, server-side forms, authentication and security.
- Live server document-root configuration.

This is a partial source inventory, not a completed security or production audit.
