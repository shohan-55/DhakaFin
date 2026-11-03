(() => {
    'use strict';
    const q = (s, r = document) => r.querySelector(s);
    const qa = (s, r = document) => [...r.querySelectorAll(s)];

    const trigger = q('[data-v2-menu]'), panel = q('#v2-mobile');
    if (trigger && panel) {
        trigger.addEventListener('click', () => {
            let open = panel.classList.toggle('open');
            trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
            trigger.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                panel.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    qa('[data-fill-demo]').forEach((btn) =>
        btn.addEventListener('click', () => {
            const f = btn.closest('form');
            if (!f) return;
            qa('input:not([type=hidden]),select,textarea', f).forEach((el) => {
                if (el.type === 'checkbox') el.checked = true;
                else if (el.tagName === 'SELECT') {
                    if (el.options.length > 1) el.selectedIndex = 1;
                } else if (el.type === 'number') {
                    if (el.name === 'income' || el.name === 'profit') el.value = '1200000';
                    else if (el.name === 'other' || el.name === 'adjustment') el.value = '150000';
                    else if (el.name === 'investment' || el.name === 'deduction') el.value = '200000';
                    else if (el.name === 'qty') el.value = '2';
                    else if (el.name === 'price') el.value = '45000';
                    else el.value = '125000';
                } else if (el.type === 'date') el.value = '2026-11-15';
                else if (el.type === 'month') el.value = '2026-11';
                else if (el.type === 'email') el.value = 'client@dhakafin.com';
                else if (el.type === 'text') {
                    if (el.name === 'invoice') el.value = 'M63-2026-105';
                    else if (el.name === 'supplier') el.value = 'DhakaFin Advisory Ltd.';
                    else if (el.name === 'buyer') el.value = 'Apex Enterprise Ltd.';
                    else if (el.name === 'item') el.value = 'Statutory Audit & VAT Advisory';
                    else el.value = 'ITA 2023 / VAT Act 2012';
                }
            });
            // Automatically trigger form calculation so user sees live results immediately
            f.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        })
    );
})();
