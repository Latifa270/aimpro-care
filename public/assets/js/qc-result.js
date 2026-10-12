document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const form = document.querySelector('form[action*="/result"]');

    if (!form) {
        return;
    }

    const hasFailedChecklist = form.dataset.hasFailed === 'true';

    const resultInputs   = document.querySelectorAll('input[name="result"]');
    const reasonWrapper  = document.getElementById('decisionReasonWrapper');
    const reasonInput    = document.getElementById('notes');

    if (!reasonWrapper || !reasonInput) {
        return;
    }

    const updateDecisionReason = () => {
        const selectedResult = document.querySelector('input[name="result"]:checked');

        const requiresReason = (
            hasFailedChecklist &&
            selectedResult &&
            selectedResult.value === 'Lolos QC'
        );

        reasonWrapper.style.display = requiresReason ? 'block' : 'none';
        reasonInput.required = requiresReason;

        if (!requiresReason) {
            reasonInput.value = '';
        }
    };

    resultInputs.forEach((input) => {
        input.addEventListener('change', updateDecisionReason);
    });

    updateDecisionReason();
});