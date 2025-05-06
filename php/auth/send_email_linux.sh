#!/bin/bash

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
{
  echo "[$DATE] === send_email.sh 실행됨 ==="
  echo "[$DATE] 이메일: $EMAIL"
  echo "[$DATE] 제목: $TITLE"
  echo "[$DATE] 임시 파일 경로: $BODY_FILE / $ALT_FILE"
} >> "$LOGFILE"

# PHP 스크립트를 백그라운드에서 실행
(
  /usr/local/php/bin/php /home1/sss5593/public_html/php/auth/send_email.php "$EMAIL" "$TITLE" "$BODY_FILE" "$ALT_FILE" >> "$LOGFILE" 2>&1
  STATUS=$?

  DATE_DONE=$(date '+%Y-%m-%d %H:%M:%S')
  if [ $STATUS -eq 0 ]; then
    echo "[$DATE_DONE] ✅ PHP 스크립트 정상 실행 완료" >> "$LOGFILE"
  else
    echo "[$DATE_DONE] ❌ PHP 스크립트 실행 중 오류 발생 (exit code $STATUS)" >> "$LOGFILE"
  fi

  # 임시 파일 삭제
  rm -f "$BODY_FILE" "$ALT_FILE"
  echo "[$DATE_DONE] === 종료 ===" >> "$LOGFILE"
  echo "" >> "$LOGFILE"
) &
