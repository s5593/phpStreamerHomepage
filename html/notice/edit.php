<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if (!is_admin()) show_alert_and_back('접근 권한이 없습니다.');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') show_alert_and_back('잘못된 접근입니다.');
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) show_alert_and_back('CSRF 오류');

$id = intval($_POST['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM board_notice WHERE id = ? AND is_use = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) show_alert_and_back('수정할 글이 존재하지 않거나 삭제되었습니다.');

$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>공지사항 수정</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Toast UI Editor CDN -->
    <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">

    <!-- 프로젝트용 스타일 -->
    <link rel="stylesheet" href="/css/notice/style.css">
    <link rel="stylesheet" href="/css/editor.css">
</head>
<body>
    <?php include_once(__DIR__ . '/../../header.php'); ?>
    <div class="notice">
        <h2 class="notice__title">공지사항 수정</h2>

        <form action="/php/notice/update.php" method="POST" id="noticeForm">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="id" value="<?= $post['id'] ?>">

            <!-- 제목 입력 -->
            <div class="notice__input-wrap">
                <input type="text" name="subject" id="subject" 
                    placeholder="제목을 입력하세요"
                    value="<?= htmlspecialchars($post['subject']) ?>"
                    required>
            </div>

            <!-- 본문 에디터 -->
            <div id="editor" style="margin-bottom: 20px;"></div>
            <textarea name="content" id="content" style="display: none;"></textarea>

            <!-- 버튼 그룹 -->
            <div class="notice__btn-group">
                <button type="submit" class="notice__btn notice__btn--submit">수정 완료</button>
                <a href="view.php?id=<?= $post['id'] ?>" class="notice__btn notice__btn--back">글 보기</a>
            </div>
        </form>
    </div>
    <?php include_once(__DIR__ . '/../../footer.php'); ?>
    <!-- Toast UI Editor JS -->
    <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
    <script src="/js/editor.js"></script>

    <!-- 수정 시 기존 내용 삽입 -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editor = toastui.Editor.factory({
                el: document.querySelector('#editor'),
                height: '500px',
                initialEditType: 'markdown',
                previewStyle: 'vertical',
                hideModeSwitch: false,
                hooks: {
                    addImageBlobHook: async (blob, callback) => {
                        const formData = new FormData();
                        formData.append('image', blob);

                        const res = await fetch('/php/notice/upload_image.php', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();
                        callback(data.url, '이미지');
                    }
                }
            });

            editor.setHTML(`<?= addslashes($post['content']) ?>`);

            document.querySelector('#noticeForm').addEventListener('submit', (e) => {
                if (!confirm(`수정 하시겠습니까?`)) {
                    e.preventDefault();
                    return;
                }
                document.querySelector('#content').value = editor.getHTML();
            });
        });
    </script>
</body>
</html>
