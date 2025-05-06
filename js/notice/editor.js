document.addEventListener('DOMContentLoaded', () => {
  const editorElement = document.querySelector('#editor');
  const form = document.querySelector('form[data-editor-target="notice"]');
  const contentInput = document.getElementById('content');

  if (!editorElement || !form || !contentInput) {
    console.warn("[Notice Editor] editor, form, content 요소 중 하나 이상 누락됨");
    return;
  }

  // 전역 editor로 저장
  window.editor = new toastui.Editor({
    el: editorElement,
    height: '500px',
    initialEditType: 'markdown', // 공지사항은 wysiwyg 기본
    previewStyle: 'vertical',
    hideModeSwitch: false, // Markdown/Preview 탭 표시
    initialValue: '',
    hooks: {
      addImageBlobHook: async (blob, callback) => {
        const formData = new FormData();
        formData.append('image', blob);
        try {
          const res = await fetch('/php/notice/upload_image.php', {
            method: 'POST',
            body: formData
          });
          const data = await res.json();
          if (data.success) {
            callback(data.url, '업로드 이미지');
          } else {
            alert('이미지 업로드 실패: ' + data.message);
          }
        } catch (e) {
          alert('이미지 업로드 중 오류가 발생했습니다.');
          console.error("[Notice Editor] 이미지 업로드 에러:", e);
        }
      }
    }
  });

  // 폼 제출할 때 editor 내용을 contentInput에 담기
  form.addEventListener('submit', (e) => {
    const mode = form.querySelector('input[name="id"]') ? '수정' : '저장';
    if (!confirm(`${mode}하시겠습니까?`)) {
      e.preventDefault();
      return;
    }
    contentInput.value = window.editor.getHTML(); // ⭐ HTML 저장
  });
});
