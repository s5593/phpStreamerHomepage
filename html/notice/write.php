<?php
include_once(__DIR__ . '/../../lib/common.php');
if ($_SESSION['mb_level'] < 3) exit('관리자만 작성 가능합니다.');

$csrf_token = generate_csrf_token();
?>

<h2>글쓰기</h2>
<form action="/php/notice/create.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    제목: <input type="text" name="subject"><br><br>
    내용:<br>
    <textarea name="content" rows="10" cols="80"></textarea><br>
    <input type="submit" value="등록">
</form>
