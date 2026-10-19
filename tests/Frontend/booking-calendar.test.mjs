import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';
import vm from 'node:vm';

// Exercise the actual inline calendar without requiring a browser or a database.
function calendar({ fullDates = [], weekdays = [1, 2, 3, 4, 5, 6, 7] } = {}) {
    const view = readFileSync(new URL('../../resources/views/welcome.blade.php', import.meta.url), 'utf8');
    const source = view.slice(view.indexOf('const unavailableBookingDates ='), view.indexOf('const mobileMenuButton ='))
        .replace('@json($unavailableBookingDates ?? [])', JSON.stringify(fullDates))
        .replace('@json($experienceAvailability ?? [])', JSON.stringify({ Esperienza: weekdays }))
        .replace('@json($calendarTranslations)', JSON.stringify({ weekdays: ['L', 'M', 'M', 'G', 'V', 'S', 'D'] }))
        .replace("@json(now(config('app.display_timezone', 'Europe/Rome'))->toDateString())", '"2026-10-19"')
        .replace("@json(app()->getLocale() === 'en' ? 'en-GB' : 'it-IT')", '"it-IT"');
    const input = { value: '' };
    const experience = { value: 'Esperienza', addEventListener() {} };
    const clicks = new Map();
    const element = {
        innerHTML: '',
        querySelectorAll(selector) {
            if (selector !== '[data-date]:not(:disabled)') return [];
            return [...this.innerHTML.matchAll(/<button\b[^>]*data-date="([^"]+)"[^>]*>/g)]
                .filter(([tag]) => !/\sdisabled(?:\s|>)/.test(tag))
                .map(([, date]) => ({
                    dataset: { date },
                    addEventListener(type, callback) { clicks.set(date, callback); },
                }));
        },
    };
    const document = {
        getElementById(id) {
            return {
                'booking-data_ora_preferita': input,
                'booking-esperienza': experience,
                'booking-availability-calendar': element,
            }[id];
        },
    };
    class BrowserDate extends Date {
        constructor(...args) {
            // The browser's local date differs from the site's Italian date.
            super(...(args.length ? args : ['2026-10-20T01:30:00']));
        }
    }
    vm.runInNewContext(source, { document, Date: BrowserDate });

    return {
        input,
        isDisabled(date) {
            const tag = element.innerHTML.match(new RegExp(`<button\\b[^>]*data-date="${date}"[^>]*>`))?.[0];
            assert.ok(tag, `Missing calendar day ${date}`);
            return /\sdisabled(?:\s|>)/.test(tag);
        },
        click(date) { clicks.get(date)?.(); },
    };
}

test('past days and today are disabled; tomorrow can be selected using the Italian date', () => {
    const page = calendar();
    assert.ok(page.isDisabled('2026-10-18'));
    assert.ok(page.isDisabled('2026-10-19'));
    assert.equal(page.isDisabled('2026-10-20'), false);
    page.click('2026-10-19');
    assert.equal(page.input.value, '');
    page.click('2026-10-20');
    assert.equal(page.input.value, '2026-10-20');
});

test('full days remain disabled after the date comparison fix', () => {
    const page = calendar({ fullDates: ['2026-10-20'] });
    assert.ok(page.isDisabled('2026-10-20'));
    assert.equal(page.isDisabled('2026-10-21'), false);
});

test('the selected experience weekday restrictions still apply', () => {
    const page = calendar({ weekdays: [4] });
    assert.ok(page.isDisabled('2026-10-20'));
    assert.ok(page.isDisabled('2026-10-21'));
    assert.equal(page.isDisabled('2026-10-22'), false);
});
