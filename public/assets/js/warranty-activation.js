'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('waUnitSearch');
    const resetButton = document.getElementById('waResetSearch');
    const resultLabel = document.getElementById('waResultLabel');
    const searchEmpty = document.getElementById('waSearchEmpty');

    const rows = Array.from(
        document.querySelectorAll('[data-wa-row]')
    );

    const totalRows = rows.length;

    if (!searchInput || totalRows === 0) {
        return;
    }

    const normalize = (value) => {
        return String(value ?? '')
            .toLocaleLowerCase('id-ID')
            .trim();
    };

    const updateSearch = () => {
        const keyword = normalize(searchInput.value);

        let visibleCount = 0;

        rows.forEach((row) => {
            const searchText = normalize(row.dataset.search);

            const isMatch =
                keyword === '' ||
                searchText.includes(keyword);

            row.hidden = !isMatch;

            if (isMatch) {
                visibleCount++;
            }
        });

        if (resultLabel) {
            resultLabel.textContent = visibleCount + ' Unit';
        }

        if (searchEmpty) {
            searchEmpty.hidden = visibleCount !== 0;
        }
    };

    searchInput.addEventListener('input', updateSearch);

    const resetSearch = () => {
        searchInput.value = '';
        updateSearch();
        searchInput.focus();
    };

    if (resetButton) {
        resetButton.addEventListener('click', resetSearch);
    }

    document.querySelectorAll('[data-copy]').forEach((button) => {
        button.addEventListener('click', async () => {
            const value = button.dataset.copy;

            if (!value) {
                return;
            }

            try {
                await navigator.clipboard.writeText(value);

                const originalHTML = button.innerHTML;

                button.innerHTML = '<i class="bi bi-check-lg"></i>';

                window.setTimeout(() => {
                    button.innerHTML = originalHTML;
                }, 1200);

            } catch (error) {
                console.error(
                    'Gagal menyalin serial number:',
                    error
                );

                window.prompt(
                    'Salin serial number berikut:',
                    value
                );
            }
        });
    });
});