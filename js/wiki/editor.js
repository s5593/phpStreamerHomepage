window.addEventListener('DOMContentLoaded', () => {
    const quill = new Quill('#editor', {
      theme: 'snow',
      placeholder: '내용을 입력하세요...',
      modules: {
        toolbar: [
            [{ header: [1, 2, 3, 4, 5, 6, false] }],     // 제목 1~6단계
            ['bold', 'italic', 'underline', 'strike'],   // 굵게, 기울임, 밑줄, 취소선
            [{ color: [] }, { background: [] }],         // 글자 색상, 배경 색상
            [{ script: 'sub' }, { script: 'super' }],     // 위첨자, 아래첨자
            ['blockquote', 'code-block'],                // 인용구, 코드블럭
            [{ list: 'ordered' }, { list: 'bullet' }],    // 순서있는 목록, 순서없는 목록
            [{ indent: '-1' }, { indent: '+1' }],         // 들여쓰기/내어쓰기
            [{ direction: 'rtl' }],                      // 오른쪽 정렬 지원
            [{ align: [] }],                             // 정렬 (왼쪽, 가운데, 오른쪽, 양쪽)
            ['link', 'image', 'video'],                  // 링크, 이미지, 비디오 삽입
            ['clean']                                    // 서식 지우기
        ]
      }
    });
  
    // 전역 참조
    window.editor = quill;
  
    if (window.postContent) {
        quill.clipboard.dangerouslyPasteHTML(window.postContent);
    }
    
    const form = document.getElementById('wikiForm');
    const hiddenTextarea = document.getElementById('content');
  
    if (form && hiddenTextarea) {
      form.addEventListener('submit', function (e) {
        if (window.editor) {
          hiddenTextarea.value = window.editor.root.innerHTML;
        }
      });
    }
  });