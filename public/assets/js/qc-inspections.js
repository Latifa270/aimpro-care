document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const searchInput = document.getElementById('unitSearch');
    const countBadge  = document.getElementById('unitResultCount');
    const emptyState  = document.getElementById('unitSearchEmpty');

    const rows = Array.from(
        document.querySelectorAll('[data-unit-row]')
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

        countBadge.textContent = count + ' Unit';
    };

    const updateEmptyState = (visibleCount, hasQuery) => {
        if (!emptyState) {
            return;
        }

        emptyState.hidden = !(hasQuery && visibleCount === 0);
    };

    const filterUnits = () => {
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

    searchInput.addEventListener('input', filterUnits);

    filterUnits();
});