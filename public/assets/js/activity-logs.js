document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const searchInput = document.getElementById('logSearch');
    const countBadge  = document.getElementById('logResultCount');
    const emptyState  = document.getElementById('logSearchEmpty');

    const rows = Array.from(
        document.querySelectorAll('[data-log-row]')
    );

    if (!searchInput || rows.length === 0) {
        return;
    }

    const normalize = (value) => {
        return String(value ?? '')
            .toLowerCase()
            .trim();
    };

    const updateResultCount = (count) => {
        if (!countBadge) {
            return;
        }

        countBadge.textContent = count + ' Aktivitas';
    };

    const updateEmptyState = (visibleCount, hasQuery) => {
        if (!emptyState) {
            return;
        }

        emptyState.hidden = !(hasQuery && visibleCount === 0);
    };

    const filterLogs = () => {
        const query = normalize(searchInput.value);

        let visibleCount = 0;

        rows.forEach((row) => {
            const searchText = normalize(
                row.dataset.search || row.textContent
            );

            const matched =
                query === '' ||
                searchText.includes(query);

            row.hidden = !matched;

            if (matched) {
                visibleCount += 1;
            }
        });

        updateResultCount(visibleCount);
        updateEmptyState(visibleCount, query !== '');
    };

    searchInput.addEventListener('input', filterLogs);

    filterLogs();
});