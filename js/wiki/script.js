// window.addEventListener('DOMContentLoaded', () => {
//   const videoBtn = document.getElementById('videoUploadBtn');
//   if (videoBtn) {
//     videoBtn.addEventListener('click', () => {
//       const input = document.createElement('input');
//       input.type = 'file';
//       input.accept = 'video/mp4';
//       input.style.display = 'none';

//       input.addEventListener('change', async () => {
//         const file = input.files[0];
//         if (!file) return;
//         if (file.size > 50 * 1024 * 1024) {
//           alert('50MB 이하의 mp4만 가능합니다.');
//           return;
//         }

//         const formData = new FormData();
//         formData.append('file', file);

//         try {
//           const res = await fetch('/php/wiki/upload_video.php', {
//             method: 'POST',
//             body: formData
//           });

//           const result = await res.json();
//           if (result.success) {
//             tinymce.activeEditor.execCommand('mceInsertContent', false, result.html);
//           } else {
//             alert(result.message || '업로드 실패');
//           }
//         } catch (err) {
//           alert('서버 오류 발생');
//         }
//       });

//       document.body.appendChild(input);
//       input.click();
//       document.body.removeChild(input);
//     });
//   }
// });



