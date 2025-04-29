document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const agreeAll = document.getElementById('agree_all');
    const checks = document.querySelectorAll('.agree-check');

    // 디버깅용 출력
    //console.log("약관 동의 JS 로딩됨");

    // 전체동의 → 하위 체크 반영
    agreeAll.addEventListener('change', function () {
        checks.forEach(cb => cb.checked = agreeAll.checked);
    });

    // 하위 체크 변경 시 전체동의 체크 상태 갱신
    checks.forEach(cb => {
        cb.addEventListener('change', function () {
            agreeAll.checked = Array.from(checks).every(c => c.checked);
        });
    });

    // 폼 유효성 검사 (하나라도 체크 안 됐으면 차단)
    form.addEventListener('submit', function (e) {
        const allChecked = Array.from(checks).every(cb => cb.checked);

        if (!allChecked) {
            alert("모든 약관에 동의해야 가입을 진행할 수 있습니다.");
            e.preventDefault(); // 실제로 제출 차단
            return false;
        }
    });
});
