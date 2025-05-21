let editor;

document.addEventListener('DOMContentLoaded', () => {
  editor = new toastui.Editor({
    el: document.querySelector('#editor'),
    height: '500px',
    initialEditType: 'wysiwyg',
    previewStyle: 'vertical',
    hideModeSwitch: true,       // 마크다운 탭 제거
    toolbarItems: [
      ['image']                 // ✅ 이미지 버튼만 표시
    ]
  });
});


function handleSubmitEditor() {
    const html = editor.getHTML();
    if (new Blob([html]).size > 1024 * 1024) {
    alert('본문 용량이 너무 큽니다. 1MB 이하로 작성해주세요.');
    return false;
    }
    document.getElementById('content-hidden').value = html;
    return true;
}
