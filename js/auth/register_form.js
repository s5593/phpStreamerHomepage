document.addEventListener('DOMContentLoaded', () => {
    // ✅ 아이디 중복 확인
    const btnCheckId = document.getElementById('btn_check_id');
    const idInput = document.getElementById('mb_id');
    const idMsg = document.getElementById('id_check_message');
    let idValid = false;
  
    if (btnCheckId && idInput && idMsg) {
      btnCheckId.addEventListener('click', () => {
        const val = idInput.value.trim();
  
        if (val.length < 4 || !/^[a-zA-Z0-9]+$/.test(val)) {
          idMsg.textContent = '4자 이상, 영어/숫자만 가능합니다';
          idMsg.className = 'input-help invalid';
          idValid = false;
          return;
        }
  
        fetch('../../php/auth/check_id.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'mb_id=' + encodeURIComponent(val)
        })
        .then(res => res.json())
        .then(data => {
          idMsg.textContent = data.message;
          if (data.status === 'ok') {
            idMsg.className = 'input-help valid';
            idValid = true;
          } else {
            idMsg.className = 'input-help invalid';
            idValid = false;
          }
        })
        .catch(() => {
          idMsg.textContent = '서버 오류입니다.';
          idMsg.className = 'input-help invalid';
          idValid = false;
        });
      });
    }
  
    // ✅ 비밀번호 검사
    const pwInput = document.getElementById('mb_password');
    const pwConfirm = document.getElementById('mb_password_confirm');
    const matchMsg = document.getElementById('pw_match_message');
  
    const rules = {
      length: {
        el: document.getElementById('pw_length'),
        check: val => val.length >= 8
      },
      max: {
        el: document.getElementById('pw_max'),
        check: val => val.length <= 30
      },
      letter: {
        el: document.getElementById('pw_letter'),
        check: val => /[a-zA-Z]/.test(val)
      },
      number: {
        el: document.getElementById('pw_number'),
        check: val => /[0-9]/.test(val)
      },
      special: {
        el: document.getElementById('pw_special'),
        check: val => /[!@#$%^&*()_\-]/.test(val)
      },
      allowed: {
        el: document.getElementById('pw_allowed'),
        check: val => /^[a-zA-Z0-9!@#$%^&*()_\-]*$/.test(val)
      }
    };
  
    function updateRuleVisuals(value) {
      const allowedRule = rules.allowed;
      const isAllowed = allowedRule.check(value);
  
      if (allowedRule.el) {
        allowedRule.el.classList.toggle('valid', isAllowed);
        allowedRule.el.classList.toggle('invalid', !isAllowed);
      }
  
      if (!isAllowed) return;
  
      Object.entries(rules).forEach(([key, rule]) => {
        if (key === 'allowed' || !rule.el) return;
        const passed = rule.check(value);
        rule.el.classList.toggle('valid', passed);
        rule.el.classList.toggle('invalid', !passed);
      });
    }
  
    function updateMatchMessage() {
      const pw = pwInput?.value || '';
      const confirm = pwConfirm?.value || '';
  
      if (!pw || !confirm) {
        matchMsg.textContent = '';
        return;
      }
  
      if (pw === confirm) {
        matchMsg.textContent = '✅ 비밀번호가 일치합니다.';
        matchMsg.style.color = '#66cc99';
      } else {
        matchMsg.textContent = '❌ 비밀번호가 일치하지 않습니다.';
        matchMsg.style.color = '#ff6666';
      }
    }
  
    if (pwInput) {
      pwInput.addEventListener('input', () => {
        updateRuleVisuals(pwInput.value);
        updateMatchMessage();
      });
    }
  
    if (pwConfirm) {
      pwConfirm.addEventListener('input', updateMatchMessage);
    }
  
    // ✅ 이메일 자동 조합 처리
    function updateEmailField() {
      const emailId = document.getElementById('mb_email_id').value.trim();
      const domainSelect = document.getElementById('mb_email_domain_select').value;
      const domainInput = document.getElementById('mb_email_domain_input').value.trim();
      const emailField = document.getElementById('mb_email');
  
      let domain = domainSelect === 'direct' ? domainInput : domainSelect;
  
      if (emailId && domain) {
        emailField.value = `${emailId}@${domain}`;
      } else {
        emailField.value = '';
      }
    }
  
    // 도메인 선택 시 직접입력 필드 표시/숨김 + 이메일 조합
    document.getElementById('mb_email_domain_select').addEventListener('change', function () {
      const domainInput = document.getElementById('mb_email_domain_input');
      if (this.value === 'direct') {
        domainInput.style.display = 'inline-block';
      } else {
        domainInput.style.display = 'none';
      }
      updateEmailField();
    });
  
    // 이메일 입력 시 자동 반영
    document.getElementById('mb_email_id').addEventListener('input', updateEmailField);
    document.getElementById('mb_email_domain_input').addEventListener('input', updateEmailField);
  
    // ✅ 회원가입 제출 시 최종 조합 + 유효성 검사
    document.getElementById('register_form').addEventListener('submit', function (e) {
      updateEmailField(); // ← 제출 직전에 이메일 조합
  
      const id = document.getElementById('mb_id').value.trim();
      const pw = pwInput.value;
      const pwConfirmVal = pwConfirm.value;
      const email = document.getElementById('mb_email').value.trim();
  
      if (id.length < 4 || !/^[a-zA-Z0-9]+$/.test(id)) {
        alert('아이디는 4자 이상, 영문 또는 숫자만 입력하세요.');
        e.preventDefault();
        return;
      }
  
      const allPwRulesValid = Array.from(document.querySelectorAll('#pw_rules li'))
        .every(li => li.classList.contains('valid'));
      if (!allPwRulesValid) {
        alert('비밀번호 조건을 모두 충족해주세요.');
        e.preventDefault();
        return;
      }
  
      if (pw !== pwConfirmVal) {
        alert('비밀번호가 일치하지 않습니다.');
        e.preventDefault();
        return;
      }
  
      if (!email.includes('@') || email.startsWith('@') || email.endsWith('@')) {
        alert('이메일 주소를 올바르게 입력하세요.');
        e.preventDefault();
        return;
      }
  
      // ✅ 모든 검사를 통과하면 제출 진행
    });
  });
  