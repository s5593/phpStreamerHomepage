<?php
include_once(__DIR__ . '/../../db/dbconn.php');

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
    <link rel="stylesheet" href="/css/admin/admin_member.css">
</head>
<body>
<?php include_once(__DIR__ . '/../../header.php'); ?>
<h2>회원 레벨 수정</h2>

<form action="../../php/admin/member_edit_action.php" method="post">
    <input type="hidden" name="mb_id" value="<?= htmlspecialchars($member['mb_id']) ?>">

    <div>
        <label>아이디: <?= htmlspecialchars($member['mb_id']) ?></label>
    </div>
    <div>
        <label for="mb_level">레벨 수정</label><br>
        <select name="mb_level" id="mb_level">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <option value="<?= $i ?>" <?= $member['mb_level'] == $i ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>
    </div>

    <div style="margin-top:15px;">
        <button type="submit" class="btn btn-edit">저장하기</button>
        <a href="member_list.php" class="btn">목록으로</a>
    </div>
</form>
<?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>
