/**
 * Pixel parity: compare each original static page against its Laravel route.
 * This is a staging-only test; it never interacts with production DhakaFin.
 * Uses the same browser engine, viewport, fonts, clock and machine.
 */
import { chromium } from 'playwright';
import pixelmatch from 'pixelmatch';
import { PNG } from 'pngjs';
import { readdir, mkdir, writeFile } from 'node:fs/promises';
import path from 'node:path';

const original = process.env.ORIGINAL_SITE_URL || 'http://127.0.0.1:8001';
const converted = process.env.LARAVEL_SITE_URL || 'http://127.0.0.1:8002';
const threshold = Number(process.env.PARITY_MAX_DIFF_RATIO || '0.012');
const reportDir = process.env.PARITY_REPORT_DIR || 'build/visual-parity';
const staticPages = (await readdir('pages')).filter(s => s.endsWith('.html')).sort();
const pages = ['index.html', ...staticPages.map(s => 'pages/' + s)];
const viewports = [
    { name: 'desktop', width: 1440, height: 900, deviceScaleFactor: 1 },
    { name: 'mobile', width: 390, height: 844, deviceScaleFactor: 1 }
];
const failures = [];
const summaries = [];
const browser = await chromium.launch({ headless: true });

await mkdir(reportDir, { recursive: true });

async function shot(page, url, file) {
    const res = await page.goto(url, { waitUntil: 'load', timeout: 25000 });
    if (!res || res.status() >= 400) {
        throw new Error(url + ': HTTP ' + (res ? res.status() : 'no response'));
    }
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await page.addStyleTag({ content:
        '*,*::before,*::after{animation:none!important;transition:none!important;scroll-behavior:auto!important}'
    });
    await Promise.race([
        page.evaluate(() => document.fonts.ready),
        new Promise(resolve => setTimeout(resolve, 2500))
    ]);
    await page.screenshot({ path: file, fullPage: true, animations: 'disabled' });
}

for (const v of viewports) {
    const context = await browser.newContext({
        viewport: { width: v.width, height: v.height },
        deviceScaleFactor: v.deviceScaleFactor,
        locale: 'en-BD',
        timezoneId: 'Asia/Dhaka',
        colorScheme: 'light',
        reducedMotion: 'reduce'
    });
    for (const slug of pages) {
        const plainPage = await context.newPage();
        const laravelPage = await context.newPage();
        const id = v.name + '-' + slug.replaceAll('/', '-');
        const aFile = path.join(reportDir, id + '-static.png');
        const bFile = path.join(reportDir, id + '-laravel.png');
        try {
            await shot(plainPage, original + '/' + slug, aFile);
            const destination = slug === 'index.html' ? '/' : '/' + slug;
            await shot(laravelPage, converted + destination, bFile);
            const { readFile } = await import('node:fs/promises');
            const a = PNG.sync.read(await readFile(aFile));
            const b = PNG.sync.read(await readFile(bFile));
            let ratio = 1;
            if (a.width === b.width && a.height === b.height) {
                const diff = new PNG({ width: a.width, height: a.height });
                const changed = pixelmatch(
                    a.data, b.data, diff.data, a.width, a.height,
                    { threshold: 0.13, includeAA: false }
                );
                ratio = changed / (a.width * a.height);
                if (ratio > threshold && failures.length < 10) {
                    await writeFile(path.join(reportDir, id + '-diff.png'),
                        PNG.sync.write(diff));
                }
            }
            const result = {
                page: slug,
                viewport: v.name,
                pixelDiffRatio: Number(ratio.toFixed(6)),
                originalSize: a.width + 'x' + a.height,
                laravelSize: b.width + 'x' + b.height,
                passed: ratio <= threshold
            };
            summaries.push(result);
            if (!result.passed) failures.push(result);
            console.log((result.passed ? 'PASS ' : 'FAIL ') + id +
                ' diff=' + (100 * ratio).toFixed(3) + '%');
        } catch (err) {
            failures.push({ page: slug, viewport: v.name, error: String(err) });
            console.error('FAIL ' + id + ': ' + err);
        } finally {
            await plainPage.close();
            await laravelPage.close();
        }
    }
    await context.close();
}
await browser.close();
await writeFile(path.join(reportDir, 'report.json'), JSON.stringify({
    pagesTested: pages.length,
    viewportCount: viewports.length,
    threshold,
    failures,
    summaries
}, null, 2));
console.log('Compared ' + summaries.length + '/' + pages.length * viewports.length +
    ' page/viewport pairs. Failures: ' + failures.length);
if (failures.length) process.exitCode = 1;
