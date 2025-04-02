<?php
if (!defined('_GNUBOARD_')) exit;

// 스타일 추가 (필요하면 커스텀 CSS 만들어서 적용 가능)
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<div class="register-form-wrap">
  <form id="fregisterform" name="fregisterform"
        action="<?php echo G5_HTTPS_BBS_URL ?>/register_form_update.php"
        method="post" autocomplete="off"
        onsubmit="return fregisterform_submit(this);">

    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <div>
      <label for="mb_id">아이디</label>
      <input type="text" name="mb_id" id="mb_id" required
             pattern="[가-힣a-zA-Z0-9]{2,12}"
             title="아이디는 한글, 영문, 숫자를 포함한 2~12자만 가능합니다. 특수문자는 사용할 수 없습니다."
             placeholder="아이디">
    </div>

    <div>
      <label for="mb_password">비밀번호</label>
      <input type="password" name="mb_password" id="mb_password" required
             pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,20}$"
             title="비밀번호는 영문, 숫자, 특수문자를 포함한 8~20자여야 합니다."
             placeholder="비밀번호">
    </div>

    <div>
      <label for="mb_password_re">비밀번호 확인</label>
      <input type="password" name="mb_password_re" id="mb_password_re" required
             placeholder="비밀번호 확인">
    </div>

    <div>
      <label for="mb_name">이름</label>
      <input type="text" name="mb_name" id="mb_name" required placeholder="이름">
    </div>

    <div>
      <label for="mb_email">이메일</label>
      <input type="email" name="mb_email" id="mb_email" required
             title="올바른 이메일 형식을 입력해주세요."
             placeholder="example@email.com">
    </div>

    <div>
      <label for="mb_homepage">채널 주소</label>
      <input type="text" name="mb_homepage" id="mb_homepage" placeholder="유튜브/스트리머 채널 URL">
    </div>

    <div>
      <label for="captcha">자동등록방지</label>
      <?php echo captcha_html(); ?>
    </div>

    <div class="btn-group">
      <button type="submit" class="btn_submit">회원가입</button>
    </div>
  </form>
</div>

<script>
function fregisterform_submit(f) {
  if (f.mb_password.value !== f.mb_password_re.value) {
    alert("비밀번호가 일치하지 않습니다.");
    f.mb_password_re.focus();
    return false;
  }

  return true;
}
</script>
