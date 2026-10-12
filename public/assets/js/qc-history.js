document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const searchInput = document.getElementById('historySearch');
    const countBadge  = document.getElementById('historyResultCount');
    const emptyState  = document.getElementById('historySearchEmpty');

    const rows = Array.from(
        document.querySelectorAll('[data-history-row]')
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
        if (!countBadge) return;
        countBadge.textContent = count + ' Riwayat';
    };

    const updateEmptyState = (visibleCount, hasQuery) => {
        if (!emptyState) return;
        emptyState.hidden = !(hasQuery && visibleCount === 0);
    };

    const filterHistory = () => {
        const query = normalize(searchInput.value);
        let visibleCount = 0;

        rows.forEach((row) => {
            const searchText = normalize(row.dataset.search);
            const matched = query === '' || searchText.includes(query);

            row.hidden = !matched;
            if (matched) visibleCount++;
        });

        updateResultCount(visibleCount);
        updateEmptyState(visibleCount, query !== '');
    };

    searchInput.addEventListener('input', filterHistory);

    filterHistory();
});