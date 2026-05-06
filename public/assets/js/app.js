document.addEventListener('DOMContentLoaded', function () {
    const flashAlerts = document.querySelectorAll('.alert-dismissible');
    flashAlerts.forEach(function (alert) {
        const close = alert.querySelector('[data-bs-dismiss="alert"]');
        if (close) {
            close.addEventListener('click', function () {
                alert.remove();
            });
        }
    });
});
