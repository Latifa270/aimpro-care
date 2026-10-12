
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('pwKeyword');

    if (searchInput) {
        searchInput.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                searchInput.value = '';
            }
        });
    }

    const downloadLink = document.querySelector('.pw-download-button');

    if (downloadLink) {
        downloadLink.addEventListener('click', (event) => {
            const confirmed = window.confirm(
                'Buka kartu garansi PDF?'
            );

            if (!confirmed) {
                event.preventDefault();
            }
        });
    }
});