<?php
include_once(__DIR__ . '/../../lib/common.php');
if (!isset($_SESSION['mb_id'])) exit('로그인 후 이용해주세요.');
$csrf_token = generate_csrf_token();
?>

<h2>영상 등록</h2>
<form action="/php/video/create.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    제목: <input type="text" name="subject"><br><br>
    영상 URL: <input type="text" name="video_url"><br><br>
    <input type="submit" value="등록">
</form>
