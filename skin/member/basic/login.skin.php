<?php
if (!defined('_GNUBOARD_')) exit;
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['login_token'])) {
    $_SESSION['login_token'] = bin2hex(random_bytes(32));
}
?>

<!-- 로그인 시작 { -->
<div id="mb_login" class="mbskin">
    <div class="mbskin_box">
        <h1><?php echo $g5['title'] ?></h1>
        
        <div class="mb_log_cate">
            <h2><span class="sound_only">회원</span>로그인</h2>
            <a href="<?php echo G5_BBS_URL ?>/register.php" class="join">회원가입</a>
        </div>

        <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
            <input type="hidden" name="token" value="<?php echo $_SESSION['login_token']; ?>">
            <input type="hidden" name="url" value="<?php echo G5_URL ?>">
            
            <fieldset id="login_fs">
                <legend>회원로그인</legend>
                
                <label for="login_id" class="sound_only">회원아이디<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="mb_id" id="login_id" required class="frm_input required" size="20" maxlength="20" placeholder="아이디">
                
                <label for="login_pw" class="sound_only">비밀번호<strong class="sound_only"> 필수</strong></label>
                <input type="password" name="mb_password" id="login_pw" required class="frm_input required" size="20" maxlength="20" placeholder="비밀번호">

                <div id="login_info">
                    <div class="login_if_lpl">
                        <a href="<?php echo G5_BBS_URL ?>/password_lost.php">ID/PW 찾기</a>  
                    </div>

                <button type="submit" class="btn_submit">로그인</button>
            </fieldset>
        </form>
    </div>
</div>

<script>
function flogin_submit(f) {
    if ($(document.body).triggerHandler('login_sumit', [f, 'flogin']) !== false) {
        return true;
    }
    return false;
}
</script>
<!-- } 로그인 끝 -->
