document.addEventListener('DOMContentLoaded', function () {
    const agreeAll = document.getElementById('agree_all');
    const agreeChecks = document.querySelectorAll('.agree-check');

    agreeAll.addEventListener('change', function () {
        agreeChecks.forEach(cb => cb.checked = agreeAll.checked);
    });

    agreeChecks.forEach(cb => {
        cb.addEventListener('change', function () {
            agreeAll.checked = Array.from(agreeChecks).every(cb => cb.checked);
        });
    });
});
