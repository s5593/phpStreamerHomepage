<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

// GET으로만 접근
$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: list.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM board_notice WHERE id = ? AND is_use = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) {
    header("Location: list.php");
    exit;
}

$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['subject']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/notice/style.css">
</head>
<body>
    <?php include_once(__DIR__ . '/../../header.php'); ?>
    <div class="notice-container">
        <h2><?= htmlspecialchars($post['subject']) ?></h2>
        <div class="notice-content">
            <?= $post['content'] ?> <!-- HTML 포함 내용 -->
        </div>
        <p class="notice-date">작성일: <?= $post['created_at'] ?></p>

        <div class="notice-btn-group">
            <?php if (is_admin()): ?>
                <form method="POST" action="edit.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <button type="submit" class="notice-btn">수정</button>
                </form>

                <form method="POST" action="/php/notice/delete.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <button type="submit" class="notice-btn">삭제</button>
                </form>
            <?php endif; ?>

            <!-- 목록으로는 관리자/일반 사용자 모두 보여야 함 -->
            <a href="list.php" class="notice-btn notice-back-btn">목록으로</a>
        </div>

    </div>
    
    <?php include_once(__DIR__ . '/../../footer.php'); ?>
    <script src="../../js/notice/script.js"></script>
</body>
</html>
