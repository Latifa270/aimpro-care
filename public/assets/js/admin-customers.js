document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const searchInput = document.getElementById('customerSearch');
    const visibleCount = document.getElementById('customerVisibleCount');
    const searchEmpty = document.getElementById('customerSearchEmpty');
    const rows = Array.from(
        document.querySelectorAll('[data-customer-row]')
    );

    if (!searchInput || !visibleCount) {
        return;
    }

    const filterCustomers = () => {
        const keyword = searchInput.value
            .trim()
            .toLowerCase();

        let matchedCount = 0;

        rows.forEach((row) => {
            const searchText = (
                row.dataset.search || ''
            ).toLowerCase();

            const isMatch = (
                keyword === '' ||
                searchText.includes(keyword)
            );

            row.hidden = !isMatch;

            if (isMatch) {
                matchedCount++;
            }
        });

        visibleCount.textContent = matchedCount;

        if (searchEmpty) {
            const hasKeyword = keyword !== '';

            searchEmpty.hidden = !hasKeyword || matchedCount > 0;
        }
    };

    searchInput.addEventListener(
        'input',
        filterCustomers
    );

    searchInput.addEventListener(
        'search',
        filterCustomers
    );
});