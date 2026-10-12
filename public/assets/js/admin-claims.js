document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const searchInput  = document.getElementById('acClaimSearch');
    const resetButton  = document.getElementById('acResetSearch');
    const resultLabel  = document.getElementById('acResultLabel');
    const searchEmpty  = document.getElementById('acSearchEmpty');

    const rows = Array.from(
        document.querySelectorAll('[data-ac-row]')
    );

    if (!searchInput || rows.length === 0) {
        return;
    }

    const normalize = (value) => {
        return String(value ?? '')
            .toLowerCase()
            .trim();
    };

    const updateResultLabel = (count) => {
        if (!resultLabel) {
            return;
        }

        resultLabel.textContent = count + ' Klaim';
    };

    const updateSearchEmpty = (visibleCount, hasQuery) => {
        if (!searchEmpty) {
            return;
        }

        searchEmpty.hidden = !(hasQuery && visibleCount === 0);
    };

    const filterClaims = () => {
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

        updateResultLabel(visibleCount);
        updateSearchEmpty(visibleCount, query !== '');
    };

    const resetSearch = () => {
        searchInput.value = '';
        filterClaims();
        searchInput.focus();
    };

    searchInput.addEventListener('input', filterClaims);

    if (resetButton) {
        resetButton.addEventListener('click', resetSearch);
    }

    filterClaims();
});