document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const searchInput = document.getElementById('awcSearch');
    const resultCount = document.getElementById('awcResultCount');
    const searchEmpty = document.getElementById('awcSearchEmpty');

    const rows = Array.from(
        document.querySelectorAll('[data-awc-row]')
    );

    if (!searchInput || !resultCount || rows.length === 0) {
        return;
    }

    const normalize = (value) => {
        return String(value ?? '')
            .toLowerCase()
            .trim();
    };

    const updateResultCount = (count) => {
        resultCount.textContent = count + ' Kartu';
    };

    const updateSearchEmpty = (visibleCount) => {
        if (!searchEmpty) {
            return;
        }

        searchEmpty.hidden = visibleCount !== 0;
    };

    const filterCards = () => {
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
        updateSearchEmpty(visibleCount);
    };

    searchInput.addEventListener('input', filterCards);

    filterCards();
});