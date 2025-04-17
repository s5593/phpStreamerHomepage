document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const id = form.mb_id;
    const pw = form.mb_password;
    const pwc = form.mb_password_confirm;
    const url = form.mb_streamer_url;

    // 이메일 관련 요소
    const emailHidden = document.getElementById('mb_email');
    const emailId = document.getElementById('mb_email_id');
    const emailSelect = document.getElementById('mb_email_domain_select');
    const emailInput = document.getElementById('mb_email_domain_input');

    // 도메인 선택 시 직접입력 토글
    emailSelect.addEventListener('change', function () {
        if (emailSelect.value === 'direct') {
            emailInput.style.display = 'inline-block';
            emailInput.required = true;
        } else {
            emailInput.style.display = 'none';
            emailInput.required = false;
        }
    });

    form.addEventListener('submit', function (e) {
        // 아이디 유효성
        if (!id.value.trim()) {
            alert('아이디를 입력해주세요.');
            id.focus();
            e.preventDefault();
            return;
        }
        if (!/^[a-zA-Z0-9]{4,}$/.test(id.value)) {
            alert('아이디는 4자 이상이며, 영어 대소문자와 숫자만 사용할 수 있습니다.');
            id.focus();
            e.preventDefault();
            return;
        }

        // 이메일 유효성
        const domain = emailSelect.value === 'direct' ? emailInput.value.trim() : emailSelect.value;
        if (!emailId.value.trim() || !domain) {
            alert('이메일을 정확히 입력해주세요.');
            e.preventDefault();
            return;
        }
        if (!/^[^@]+$/.test(emailId.value)) {
            alert('이메일 아이디 형식이 올바르지 않습니다.');
            emailId.focus();
            e.preventDefault();
            return;
        }
        if (!/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(domain)) {
            alert('도메인 형식이 올바르지 않습니다.');
            emailSelect.focus();
            e.preventDefault();
            return;
        }
        emailHidden.value = `${emailId.value.trim()}@${domain}`;

        // 비밀번호 유효성
        if (!pw.value.trim()) {
            alert('비밀번호를 입력해주세요.');
            pw.focus();
            e.preventDefault();
            return;
        }
        if (!/^[a-zA-Z0-9!@#$%^&*()_\-]{8,}$/.test(pw.value)) {
            alert('비밀번호는 8자 이상이며, 영어, 숫자, 특수문자(!@#$%^&*()-_)만 허용됩니다.');
            pw.focus();
            e.preventDefault();
            return;
        }

        // 비밀번호 확인
        if (pw.value !== pwc.value) {
            alert('비밀번호가 일치하지 않습니다.');
            pwc.focus();
            e.preventDefault();
            return;
        }
    });
});
