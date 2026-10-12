document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('publicClaimForm');

    if (!form) {
        return;
    }

    const handoverInputs = form.querySelectorAll(
        'input[name="handover_method"]'
    );

    const shippingAddressField =
        document.getElementById('shippingAddressField');

    const shippingAddress =
        document.getElementById('shipping_address');

    const complaint =
        document.getElementById('complaint');

    const complaintCount =
        document.getElementById('complaintCount');

    const photoInput =
        document.getElementById('damage_photo');

    const photoFileName =
        document.getElementById('photoFileName');

    const videoInput =
        document.getElementById('damage_video');

    const videoFileName =
        document.getElementById('videoFileName');

    const damageStartDate =
        document.getElementById('damage_start_date');

    const submitButton =
        document.getElementById('submitClaimButton');

    const updateShippingAddress = () => {
        const selected = form.querySelector(
            'input[name="handover_method"]:checked'
        );

        const isCourier =
            selected &&
            selected.value === 'Kurir';

        if (!shippingAddressField) {
            return;
        }

        shippingAddressField.hidden = !isCourier;

        if (shippingAddress) {
            shippingAddress.required = isCourier;

            if (!isCourier) {
                shippingAddress.value = '';
                shippingAddress.classList.remove('is-invalid');
            }
        }
    };

    handoverInputs.forEach((input) => {
        input.addEventListener(
            'change',
            updateShippingAddress
        );
    });

    updateShippingAddress();

    const updateComplaintCount = () => {
        if (!complaint || !complaintCount) {
            return;
        }

        const length = complaint.value.length;

        complaintCount.textContent =
            `${length.toLocaleString('id-ID')}/5000`;
    };

    if (complaint) {
        complaint.addEventListener(
            'input',
            updateComplaintCount
        );

        updateComplaintCount();
    }

    const updateFileName = (
        input,
        target,
        defaultText
    ) => {
        if (!input || !target) {
            return;
        }

        const file = input.files?.[0];

        if (!file) {
            target.textContent = defaultText;
            return;
        }

        target.textContent = file.name;
    };

    if (photoInput) {
        photoInput.addEventListener('change', () => {
            updateFileName(
                photoInput,
                photoFileName,
                'Pilih foto kerusakan'
            );
        });
    }

    if (videoInput) {
        videoInput.addEventListener('change', () => {
            updateFileName(
                videoInput,
                videoFileName,
                'Pilih video kerusakan'
            );
        });
    }

    if (damageStartDate) {
        const today = new Date();

        const year =
            today.getFullYear();

        const month =
            String(
                today.getMonth() + 1
            ).padStart(2, '0');

        const day =
            String(
                today.getDate()
            ).padStart(2, '0');

        damageStartDate.max =
            `${year}-${month}-${day}`;
    }

    let submitted = false;

    form.addEventListener('submit', (event) => {
        if (submitted) {
            event.preventDefault();
            return;
        }

        if (!form.checkValidity()) {
            return;
        }

        submitted = true;

        if (!submitButton) {
            return;
        }

        submitButton.disabled = true;

        const buttonText =
            submitButton.querySelector('span');

        const buttonIcon =
            submitButton.querySelector('i');

        if (buttonText) {
            buttonText.textContent =
                'Mengirim pengajuan...';
        }

        if (buttonIcon) {
            buttonIcon.className =
                'bi bi-arrow-repeat';
        }
    });
});