document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const agreeAll = document.getElementById('agree_all');
    const agreeTerms = document.querySelector('input[name="agree_terms"]');
    const agreePrivacy = document.querySelector('input[name="agree_privacy"]');
  
    if (!form || !agreeAll || !agreeTerms || !agreePrivacy) return;
  
    const checks = [agreeTerms, agreePrivacy];
  
    // 전체 동의 체크 → 하위 항목 모두 반영
    agreeAll.addEventListener('change', function () {
      const isChecked = agreeAll.checked;
      checks.forEach(cb => cb.checked = isChecked);
    });
  
    // 하위 체크 변경 → 전체동의 상태 갱신
    checks.forEach(cb => {
      cb.addEventListener('change', function () {
        agreeAll.checked = checks.every(c => c.checked);
      });
    });
  
    // 제출 시 검증
    form.addEventListener('submit', function (e) {
      const allChecked = checks.every(cb => cb.checked);
  
      if (!allChecked) {
        alert("모든 약관에 동의해야 회원가입을 진행할 수 있습니다.");
        e.preventDefault();
      }
    });
  });
  