<?php
include_once(__DIR__ . '/../../db/dbconn.php');
include_once(__DIR__ . '/../../lib/common.php');

// 관리자 인증
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['mb_level']) || $_SESSION['mb_level'] < 3) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='/';</script>";
    exit;
}

$mb_id = trim($_GET['mb_id'] ?? '');
if ($mb_id === '') {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 회원 정보 가져오기
$stmt = $conn->prepare("SELECT mb_no, mb_id, mb_level FROM g5_member WHERE mb_id = ?");
$stmt->bind_param("s", $mb_id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();
$stmt->close();

if (!$member) {
    echo "<script>alert('회원 정보를 찾을 수 없습니다.'); history.back();</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원 레벨 수정</title>
    <link rel="stylesheet" href="/css/admin/style.css">
</head>
<body>
    <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>
    <h2 class="admin__title">회원 레벨 수정</h2>

    <form action="../../php/admin/member_edit_action.php" method="post" class="admin__form">
      <input type="hidden" name="mb_id" value="<?= htmlspecialchars($member['mb_id']) ?>">

      <div class="admin__group">
        <label class="admin__label">아이디</label>
        <div class="admin__static-text"><?= htmlspecialchars($member['mb_id']) ?></div>
      </div>

      <div class="admin__group">
        <label for="mb_level" class="admin__label">레벨 수정</label>
        <select name="mb_level" id="mb_level" class="input--select">
            <?php for ($i = 1; $i <= 4; $i++): ?>
            <option value="<?= $i ?>" <?= $member['mb_level'] == $i ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>
      </div>


      <div class="admin__button-group">
        <button type="submit" class="button button--primary">저장하기</button>
        <a href="member_list.php" class="button button--secondary">목록으로</a>
      </div>
    </form>
  </div>
  <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>

</html>
