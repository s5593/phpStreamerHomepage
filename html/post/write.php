<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if (!current_user_no()) {
    redirect_with_message('로그인이 필요합니다.', '/html/post/list.php');
}

$csrf_token = generate_csrf_token();
include_once(__DIR__ . '/write_form.html.php');
