/*
 * DhakaFin opt-in frontend adapter.
 *
 * This script is injected by StaticPageController ONLY for explicitly enabled
 * services. It adds no new styling and leaves the original static views,
 * navigation, animations and layout files untouched.
 *
 * No rates, legal answers or statutory deadlines are hardcoded here.
 */
(() => {
    'use strict';

    const path = window.location.pathname;
    const $ = (selector, root = document) => root.querySelector(selector);
    const setText = (element, value) => { if (element) element.textContent = String(value); };
    const node = (tag, text, className) => {
        const el = document.createElement(tag);
        if (text !== undefined) el.textContent = String(text);
        if (className) el.className = className;
        return el;
    };

    async function json(url, options = {}) {
        const response = await fetch(url, {
            ...options,
            headers: { 'Accept': 'application/json', ...(options.headers || {}) },
            credentials: 'same-origin'
        });
        if (response.status === 503) {
            throw new Error('This service is not published yet.');
        }
        const payload = await response.json();
        if (!response.ok) {
            const first = payload.errors && Object.values(payload.errors)[0];
            const message = Array.isArray(first) && first.length
                ? first[0] : payload.message;
            throw new Error(message || 'Unable to retrieve verified information.');
        }
        return payload;
    }

    function appendCitation(container, url, label) {
        if (!/^https?:\/\//i.test(String(url || ''))) return;
        const link = node('a', label || 'Read original source ↗');
        link.href = url;
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        container.appendChild(link);
    }

    function setStatus(form, message, bad = false) {
        const feedback = $('.form-response', form) || $('.ai-answer');
        if (feedback) {
            feedback.textContent = message;
            feedback.setAttribute('role', 'status');
            feedback.setAttribute('aria-live', 'polite');
            if (bad) feedback.setAttribute('data-df-error', 'true');
            else feedback.removeAttribute('data-df-error');
        }
    }

    // Existing static demo forms contain no verified rates. Never infer a rate
    // from the label "rent" / "standard" / "supplies": select an effective,
    // admin-approved rule from the public rate endpoint.
    async function activateCalculator(type) {
        const form = $('form.demo-form');
        if (!form) return;

        let choices = [];
        const vatRateSelect = type === 'vat' ? $('#input-rate', form) : null;
        const referenceField = type === 'vat' ? null : $('#input-reference', form);
        const dateField = type === 'vat' ? null : $('#input-date', form);
        const suggestions = referenceField ? node('datalist') : null;
        if (suggestions) {
            suggestions.id = 'df-verified-rate-codes';
            form.appendChild(suggestions);
            referenceField.setAttribute('list', suggestions.id);
            referenceField.setAttribute('autocomplete', 'off');
        }

        async function reloadRates() {
            let loadError = 'Verified rates are currently unavailable.';
            const dateValue = dateField && dateField.value;
            if (dateValue && !/^\d{4}-\d{2}-\d{2}$/.test(dateValue)) {
                choices = [];
                setStatus(form, 'Select a valid transaction date.', true);
                return;
            }
            const dateQuery = dateValue ? '?as_of=' + encodeURIComponent(dateValue) : '';
            try {
                const result = await json('/api/v1/tax/rates' + dateQuery);
                choices = (result.data || []).filter(item =>
                    item.tax_type === type && Number.isInteger(item.id) &&
                    Number.isInteger(item.rate_basis_points)
                );
            } catch (error) {
                choices = [];
                loadError = error.message || loadError;
            }

            if (vatRateSelect) {
                vatRateSelect.replaceChildren(node('option', 'Select a verified rate'));
                vatRateSelect.options[0].value = '';
                choices.forEach(item => {
                    const option = node('option', item.category + ' — ' +
                        (item.rate_basis_points / 100).toFixed(2) + '%');
                    option.value = String(item.id);
                    vatRateSelect.appendChild(option);
                });
            }
            if (suggestions) {
                suggestions.replaceChildren();
                choices.forEach(item => {
                    const option = node('option');
                    option.value = item.code;
                    option.label = item.category + ' — ' +
                        (item.rate_basis_points / 100).toFixed(2) + '%';
                    suggestions.appendChild(option);
                });
            }
            setStatus(form, choices.length
                ? 'Select a verified effective-rate reference before calculating.'
                : 'No verified effective rate is available. ' + loadError,
                choices.length === 0);
        }

        await reloadRates();
        if (dateField) dateField.addEventListener('change', reloadRates);

        // Capture before legacy demo-submit handlers, but only on enabled pages.
        form.addEventListener('submit', async event => {
            event.preventDefault();
            event.stopImmediatePropagation();
            const matched = vatRateSelect
                ? choices.find(item => String(item.id) === vatRateSelect.value)
                : choices.find(item => item.code.toLowerCase() ===
                    String(referenceField.value || '').trim().toLowerCase());
            if (!matched) {
                setStatus(form, 'Choose a valid verified rate from the available references.', true);
                return;
            }

            const amountField = type === 'vat' ? $('#input-amount', form) : $('#input-base', form);
            const amount = amountField && amountField.value.trim();
            if (!amount || !/^(0|[1-9]\d{0,11})(?:\.\d{1,2})?$/.test(amount)) {
                setStatus(form, 'Enter a non-negative amount in BDT with up to two decimals.', true);
                return;
            }
            const mode = type === 'vat' && $('#input-method', form)?.value === 'VAT inclusive'
                ? 'inclusive' : 'exclusive';
            const submit = $('button[type="submit"]', form);
            if (submit) submit.disabled = true;
            setStatus(form, 'Checking the approved rate...');
            try {
                const result = await json('/api/v1/tax/estimate', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        tax_type: type,
                        rate_rule_id: matched.id,
                        amount_bdt: amount,
                        amount_mode: mode,
                        transaction_date: dateField && dateField.value ? dateField.value : undefined
                    })
                });
                const items = document.querySelectorAll('.formula-skeleton > div > strong');
                setText(items[0], '৳ ' + result.calculation.base_amount_bdt);
                setText(items[1], result.calculation.rate_percent + '% — ' + result.category);
                setText(items[2], '৳ ' + result.calculation.estimated_amount_bdt);
                const stamp = $('.result-stamp');
                if (stamp) stamp.textContent = 'VERIFIED RATE';
                const note = $('.notice-dark');
                if (note) {
                    note.replaceChildren(node('span', result.warning + ' '));
                    appendCitation(note, result.citation.source_url,
                        result.citation.reference_code + ' — Read original source ↗');
                }
                setStatus(form, 'Calculation completed using a verified, effective reference.');
            } catch (error) {
                setStatus(form, error.message || 'The calculation could not be completed.', true);
            } finally {
                if (submit) submit.disabled = false;
            }
        }, { capture: true });
    }

    async function activateLawSearch() {
        const form = $('[data-ai-form]');
        const answer = $('#ai-answer');
        if (!form || !answer) return;
        const input = $('input[type="search"]', form);
        if (!input) return;

        form.addEventListener('submit', async event => {
            event.preventDefault();
            event.stopImmediatePropagation();
            const question = input.value.trim();
            if (question.length < 3) {
                answer.replaceChildren(node('p', 'Enter at least three characters.'));
                return;
            }
            answer.replaceChildren(node('p', 'Searching human-verified legal sources...'));
            try {
                const data = await json('/api/v1/law-search?q=' + encodeURIComponent(question));
                answer.replaceChildren(node('div', 'VERIFIED SOURCE SEARCH', 'answer-overline'));
                answer.appendChild(node('h3', 'Original legal references'));
                answer.appendChild(node('p', data.notice ||
                    'Review original documents before relying on an interpretation.'));
                if (!Array.isArray(data.data) || !data.data.length) {
                    answer.appendChild(node('p', 'No verified records matched. Try a different legal term.'));
                }
                (data.data || []).forEach(item => {
                    const card = node('div', undefined, 'source-placeholder');
                    card.appendChild(node('strong', item.title));
                    card.appendChild(node('p',
                        [item.reference_code, item.provision_reference,
                            item.page_reference].filter(Boolean).join(' · ')));
                    if (item.summary) card.appendChild(node('p', item.summary));
                    appendCitation(card, item.source_url, 'Check original document ↗');
                    answer.appendChild(card);
                });
            } catch (error) {
                answer.replaceChildren(node('p',
                    error.message || 'Verified law search is unavailable.'));
            }
        }, { capture: true });
    }

    async function activateNotices() {
        const list = $('#library-results');
        if (!list) return;
        try {
            const response = await json('/api/v1/notices');
            list.replaceChildren();
            if (!response.data || !response.data.length) {
                list.appendChild(node('p', 'No verified published notices are available yet.'));
                return;
            }
            response.data.forEach(item => {
                const row = node('div', undefined, 'resource-row');
                row.appendChild(node('span', '▤', 'resource-icon'));
                const info = node('span');
                info.appendChild(node('strong', item.title));
                info.appendChild(node('small', item.published_at || 'Published notice'));
                row.appendChild(info);
                appendCitation(row, item.source_url, 'Original notice ↗');
                list.appendChild(row);
            });
        } catch (error) {
            list.prepend(node('p', error.message || 'Verified notices unavailable.'));
        }
    }

    async function activateDeadlines() {
        const parent = $('.result-panel');
        if (!parent) return;
        try {
            const response = await json('/api/v1/deadlines');
            const list = node('div', undefined, 'resource-list');
            list.appendChild(node('h3', 'Published upcoming deadlines'));
            if (!response.data || !response.data.length) {
                list.appendChild(node('p', 'No verified future deadlines are published.'));
            }
            (response.data || []).forEach(item => {
                const entry = node('div', undefined, 'resource-row');
                entry.appendChild(node('strong', item.title + ' — ' + item.due_on));
                entry.appendChild(node('span', item.category));
                appendCitation(entry, item.source_url, 'Official source ↗');
                list.appendChild(entry);
            });
            parent.appendChild(list);
        } catch (error) {
            parent.appendChild(node('p', error.message || 'Verified deadlines unavailable.'));
        }
    }

    async function activateInsights() {
        // Articles are appended only when explicitly published by the editor.
        const main = $('main');
        if (!main) return;
        try {
            const response = await json('/api/v1/posts');
            if (!response.data || !response.data.length) return;
            const section = node('section', undefined, 'site-container');
            section.appendChild(node('h2', 'Verified DhakaFin insights'));
            response.data.forEach(item => {
                const article = node('article', undefined, 'resource-row');
                article.appendChild(node('h3', item.title));
                article.appendChild(node('p', item.excerpt || 'Read the source for full context.'));
                appendCitation(article, item.source_url, 'Original reference ↗');
                section.appendChild(article);
            });
            main.appendChild(section);
        } catch {
            // Editorial feed not yet published; preserve existing content.
        }
    }

    if (path.endsWith('/tds-calculator.html')) activateCalculator('tds');
    else if (path.endsWith('/vds-calculator.html')) activateCalculator('vds');
    else if (path.endsWith('/vat-calculator.html')) activateCalculator('vat');
    else if (path.endsWith('/ai-tax-search.html')) activateLawSearch();
    else if (path.endsWith('/circulars.html')) activateNotices();
    else if (path.endsWith('/tax-deadlines.html')) activateDeadlines();
    else if (path.endsWith('/insights.html')) activateInsights();
})();
