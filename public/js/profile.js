document.addEventListener('DOMContentLoaded', function () {

    // Banner avatar click → scroll to form and trigger file input
    const bannerLabel = document.getElementById('avatarInputBanner');
    if (bannerLabel) {
        bannerLabel.addEventListener('change', function (e) {
            syncAvatar(e.target.files[0]);
            // Copy file to main input
            const mainInput = document.getElementById('avatarInput');
            if (mainInput) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(e.target.files[0]);
                mainInput.files = dataTransfer.files;
            }
        });
    }

    // Form avatar input
    const input = document.getElementById('avatarInput');
    if (input) {
        input.addEventListener('change', function (e) {
            syncAvatar(e.target.files[0]);
        });
    }

    function syncAvatar(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (ev) {
            const preview  = document.getElementById('avatarPreview');
            const fallback = document.getElementById('avatarFallback');
            if (preview) {
                preview.src = ev.target.result;
                preview.classList.remove('d-none');
            }
            if (fallback) fallback.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }
});
