<script>
    const MONTH_LONG = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    function islandCalendar({ today, eventDots }) {
        const t = new Date(today);
        const pad = (n) => String(n).padStart(2, '0');
        return {
            today: t,
            viewYear: t.getFullYear(),
            viewMonth: t.getMonth(),
            eventDots: eventDots || {},
            get year() { return this.viewYear; },
            get monthLabel() { return MONTH_LONG[this.viewMonth]; },
            get daysInMonth() {
                return new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
            },
            get firstDay() {
                return new Date(this.viewYear, this.viewMonth, 1).getDay();
            },
            get emptyCells() { return Array.from({length: this.firstDay}, (_, i) => i); },
            get days() { return Array.from({length: this.daysInMonth}, (_, i) => i + 1); },
            isoFor(d) {
                return `${this.viewYear}-${pad(this.viewMonth + 1)}-${pad(d)}`;
            },
            dotsForDay(d) {
                return this.eventDots[this.isoFor(d)] || [];
            },
            hasEvent(d) {
                return this.dotsForDay(d).length > 0;
            },
            isToday(d) {
                return d === this.today.getDate()
                    && this.viewYear === this.today.getFullYear()
                    && this.viewMonth === this.today.getMonth();
            },
            prev() {
                if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; }
                else this.viewMonth--;
            },
            next() {
                if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; }
                else this.viewMonth++;
            },
            goToday() {
                this.viewYear = this.today.getFullYear();
                this.viewMonth = this.today.getMonth();
            },
        };
    }

    function islandGroupFilter() {
        return {
            active: new Set(),
            total: 0,
            init() {
                document.querySelectorAll('[data-event-card]').forEach(card => {
                    const g = card.dataset.group;
                    if (g) this.active.add(g);
                });
                // Also include all groups from the sidebar (covers case where no events yet for a group)
                document.querySelectorAll('[data-group]').forEach(el => {
                    if (el.dataset.group) this.active.add(el.dataset.group);
                });
                this.total = this.active.size;
                this.$watch('active', () => this.apply());
                this.apply();
            },
            toggle(key) {
                const n = new Set(this.active);
                n.has(key) ? n.delete(key) : n.add(key);
                this.active = n;
            },
            only(key) {
                this.active = new Set([key]);
            },
            toggleAll() {
                if (this.active.size === this.total) {
                    this.active = new Set();
                } else {
                    const all = new Set();
                    document.querySelectorAll('[data-group]').forEach(el => {
                        if (el.dataset.group) all.add(el.dataset.group);
                    });
                    this.active = all;
                }
            },
            apply() {
                document.querySelectorAll('[data-event-card]').forEach(card => {
                    const match = this.active.has(card.dataset.group);
                    card.style.display = match ? '' : 'none';
                });
                // Hide month headings whose events are all filtered out
                document.querySelectorAll('[data-month-group]').forEach(group => {
                    const cards = group.querySelectorAll('[data-event-card]');
                    const anyVisible = Array.from(cards).some(c => c.style.display !== 'none');
                    group.style.display = anyVisible ? '' : 'none';
                });
                // Empty state — scoped to the upcoming grid
                const empty = document.getElementById('event-grid-empty');
                const grid = document.getElementById('event-grid');
                if (empty && grid) {
                    const upcomingCards = grid.querySelectorAll('[data-event-card]');
                    const visibleUpcoming = Array.from(upcomingCards).filter(c => c.style.display !== 'none').length;
                    const hasCards = upcomingCards.length > 0;
                    if (hasCards && visibleUpcoming === 0) {
                        empty.classList.remove('hidden');
                        grid.classList.add('hidden');
                    } else {
                        empty.classList.add('hidden');
                        grid.classList.remove('hidden');
                    }
                }
            },
        };
    }

    window.downloadIcs = function (ev) {
        const btn = ev?.currentTarget ?? event.currentTarget;
        if (!btn) return;
        const title = btn.dataset.icsTitle;
        const start = btn.dataset.icsStart;
        const end = btn.dataset.icsEnd;
        const loc = btn.dataset.icsLoc || '';
        const desc = btn.dataset.icsDesc || '';
        const uid = Math.random().toString(36).slice(2) + '@meetup.mu';
        const ics = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//meetup.mu//EN',
            'BEGIN:VEVENT',
            `UID:${uid}`,
            `DTSTAMP:${start}`,
            `DTSTART:${start}`,
            `DTEND:${end}`,
            `SUMMARY:${title.replace(/[\r\n,;]/g, ' ')}`,
            `LOCATION:${loc.replace(/[\r\n,;]/g, ' ')}`,
            `DESCRIPTION:${desc.replace(/[\r\n,;]/g, ' ')}`,
            'END:VEVENT',
            'END:VCALENDAR',
        ].join('\r\n');
        const blob = new Blob([ics], { type: 'text/calendar;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${title.replace(/[^a-z0-9]+/gi, '-').toLowerCase()}.ics`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    };

    function huePicker() {
        return {
            open: false,
            hue: parseInt(localStorage.getItem('hue') ?? '240', 10),
            presets: [
                { h: 240, label: 'Denim' },
                { h: 200, label: 'Sky' },
                { h: 170, label: 'Teal' },
                { h: 130, label: 'Moss' },
                { h: 60,  label: 'Sand' },
                { h: 20,  label: 'Terra' },
                { h: 330, label: 'Rose' },
                { h: 290, label: 'Violet' },
            ],
            apply() {
                document.documentElement.style.setProperty('--hue', this.hue);
                localStorage.setItem('hue', this.hue);
            },
        };
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('islandCalendar', islandCalendar);
        Alpine.data('islandGroupFilter', islandGroupFilter);
        Alpine.data('huePicker', huePicker);
    });
</script>
