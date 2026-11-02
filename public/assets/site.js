(() => {
    'use strict';
    const q = (s, root = document) => root.querySelector(s),
          qa = (s, root = document) => [...root.querySelectorAll(s)];

    const menu = q('[data-mobile-menu]'), panel = q('#mobile-menu');
    if (menu && panel) {
        menu.addEventListener('click', () => {
            let open = panel.classList.toggle('open');
            menu.setAttribute('aria-expanded', String(open));
            menu.textContent = open ? '×' : '☰';
        });
        qa('#mobile-menu a').forEach((a) =>
            a.addEventListener('click', () => {
                panel.classList.remove('open');
                menu.setAttribute('aria-expanded', 'false');
                menu.textContent = '☰';
            })
        );
    }

    const toast = q('#toast');
    let hide;
    function notify(msg) {
        if (!toast) return;
        toast.textContent = msg;
        toast.classList.add('show');
        clearTimeout(hide);
        hide = setTimeout(() => toast.classList.remove('show'), 4800);
    }

    qa('[data-toast]').forEach((el) =>
        el.addEventListener('click', () => {
            if (el.classList.contains('ghost-btn')) return;
            notify(el.dataset.toast || 'Action completed.');
        })
    );

    qa('[data-password-toggle]').forEach((btn) =>
        btn.addEventListener('click', () => {
            const input = q('input[type=password],input[type=text]', btn.closest('.form-field'));
            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
                btn.textContent = input.type === 'password' ? 'Show' : 'Hide';
            }
        })
    );

    qa('[data-filter-input]').forEach((input) => {
        const box = document.getElementById(input.dataset.filterInput);
        if (!box) return;
        input.addEventListener('input', () => {
            let term = input.value.trim().toLowerCase();
            qa('a,.resource-row,.data-table tbody tr', box).forEach(
                (x) => (x.hidden = !x.textContent.toLowerCase().includes(term))
            );
        });
    });

    const form = q('[data-ai-form]');
    if (form) {
        const input = q('input', form);
        qa('[data-example-q]').forEach((btn) =>
            btn.addEventListener('click', () => {
                input.value = btn.dataset.exampleQ;
                input.focus();
                form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
            })
        );
    }
})();
