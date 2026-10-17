/**
 * Browser smoke tests for explicitly enabled tools against isolated CI SQLite.
 * Synthetic fixtures are never valid statutory advice or published production data.
 */
import { chromium } from 'playwright';
import assert from 'node:assert/strict';

const base = process.env.BROWSER_TEST_URL || 'http://127.0.0.1:8003';
const browser = await chromium.launch();
const errors = [];
const page = await browser.newPage({
    viewport: { width: 1366, height: 768 },
    locale: 'en-BD',
    timezoneId: 'Asia/Dhaka'
});
page.on('pageerror', error => errors.push(error.message));

async function open(slug) {
    const response = await page.goto(base + slug, {
        waitUntil: 'load',
        timeout: 20000
    });
    assert.equal(response.status(), 200, slug + ' status');
}

try {
    await open('/');
    const manifest = await page.evaluate(() => window.DhakaFinFeatures);
    assert.equal(manifest['tds-calculator.html'], true, 'homepage enables reviewed TDS link');
    assert.equal(manifest['client-services.html'], undefined, 'portal must stay disabled');

    await open('/pages/tds-calculator.html');
    await page.waitForFunction(() =>
        document.querySelector('#df-verified-rate-codes option') !== null
    );
    await page.locator('#input-base').fill('1000.00');
    await page.locator('#input-reference').fill('CI-TDS-5PCT');
    await page.locator('.demo-form button[type="submit"]').click();
    await page.waitForFunction(() =>
        document.querySelector('.formula-skeleton')?.textContent?.includes('50.00')
    );
    assert.match(await page.locator('.notice-dark').textContent(), /Illustrative fixed-rate arithmetic/i);
    assert.equal(await page.locator('#df-service-availability').count(), 0, 'no legacy demo popup');
    console.log('PASS TDS amount and verified reference');

    await open('/pages/vds-calculator.html');
    await page.waitForFunction(() =>
        document.querySelector('#df-verified-rate-codes option') !== null
    );
    await page.locator('#input-base').fill('1000.00');
    await page.locator('#input-reference').fill('CI-VDS-5PCT');
    await page.locator('.demo-form button[type="submit"]').click();
    await page.waitForFunction(() =>
        document.querySelector('.formula-skeleton')?.textContent?.includes('50.00')
    );
    console.log('PASS VDS effective reference');

    await open('/pages/vat-calculator.html');
    await page.waitForFunction(() =>
        document.querySelectorAll('#input-rate option').length > 1
    );
    await page.locator('#input-amount').fill('1150.00');
    await page.locator('#input-method').selectOption({ label: 'VAT inclusive' });
    await page.locator('#input-rate').selectOption({ index: 1 });
    await page.locator('.demo-form button[type="submit"]').click();
    await page.waitForFunction(() =>
        document.querySelector('.formula-skeleton')?.textContent?.includes('150.00')
    );
    console.log('PASS VAT-inclusive calculation');

    await open('/pages/ai-tax-search.html');
    await page.locator('[data-ai-form] input').fill('illustrative');
    await page.locator('[data-ai-form] button').click();
    await page.waitForFunction(() =>
        document.querySelector('#ai-answer')?.textContent?.includes('CI illustrative legal reference')
    );
    console.log('PASS cited law search');

    await open('/pages/client-services.html');
    assert.match(await page.locator('body').textContent(), /Coming Soon/i);
    console.log('PASS unreleased client portal remains disabled');

    assert.deepEqual(errors, [], 'No unhandled frontend JavaScript errors');
    console.log('All enabled-feature browser smoke tests passed.');
} finally {
    await browser.close();
}
