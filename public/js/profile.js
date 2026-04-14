document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('avatarInput');
    if (!input) return;

    input.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (ev) {
            const preview  = document.getElementById('avatarPreview');
            const fallback = document.getElementById('avatarFallback');
            preview.src = ev.target.result;
            preview.classList.remove('d-none');
            if (fallback) fallback.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });
});
