#!/bin/bash

# 인자: $1=email, $2=title, $3=base64 body, $4=base64 altBody

LOGFILE="/home1/sss5593/public_html/logs/send_email.log"
DATE=$(date '+%Y-%m-%d %H:%M:%S')

EMAIL="$1"
TITLE="$2"
BODY_B64="$3"
ALT_B64="$4"

BODY_FILE="/tmp/email_body_$$.b64"
ALT_FILE="/tmp/email_alt_$$.b64"

# 임시 파일 생성
echo "$BODY_B64" > "$BODY_FILE"
echo "$ALT_B64" > "$ALT_FILE"

# 로그 기록
echo "[$DATE] === send_email.sh 실행됨 ===" >> "$LOGFILE"
echo "[$DATE] 이메일: $EMAIL" >> "$LOGFILE"
echo "[$DATE] 제목: $TITLE" >> "$LOGFILE"
echo "[$DATE] 임시 파일 경로: $BODY_FILE / $ALT_FILE" >> "$LOGFILE"

/usr/local/php/bin/php /home1/sss5593/public_html/php/auth/send_email.php "$EMAIL" "$TITLE" "$BODY_FILE" "$ALT_FILE" >> "$LOGFILE" 2>&1

if [ $? -eq 0 ]; then
  echo "[$DATE] ✅ PHP 스크립트 정상 실행 완료" >> "$LOGFILE"
else
  echo "[$DATE] ❌ PHP 스크립트 실행 중 오류 발생" >> "$LOGFILE"
fi

# 임시 파일 삭제
rm -f "$BODY_FILE" "$ALT_FILE"

echo "[$DATE] === 종료 ===" >> "$LOGFILE"
echo "" >> "$LOGFILE"
