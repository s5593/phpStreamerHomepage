document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', function (e) {
        const publicKey = document.getElementById('rsa_public_key').value;

        if (!publicKey) {
            alert("🔐 암호화 키를 불러오지 못했습니다. 새로고침 후 다시 시도해주세요.");
            e.preventDefault();
            return;
        }

        const encrypt = new JSEncrypt();
        encrypt.setPublicKey(publicKey);

        // 원본 입력 필드 값 가져오기
        const idRaw = document.getElementById('mb_id').value;
        const emailRaw = document.getElementById('mb_email').value;
        const pwRaw = document.getElementById('mb_password').value;
        const pwcRaw = document.getElementById('mb_password_confirm').value;
        const urlRaw = document.getElementById('mb_streamer_url').value;

        // 암호화 후 숨겨진 필드에 대입
        form.querySelector('input[name="mb_id"]').value = encrypt.encrypt(idRaw);
        form.querySelector('input[name="mb_email"]').value = encrypt.encrypt(emailRaw);
        form.querySelector('input[name="mb_password"]').value = encrypt.encrypt(pwRaw);
        form.querySelector('input[name="mb_password_confirm"]').value = encrypt.encrypt(pwcRaw);
        form.querySelector('input[name="mb_streamer_url"]').value = encrypt.encrypt(urlRaw);
    });
});
