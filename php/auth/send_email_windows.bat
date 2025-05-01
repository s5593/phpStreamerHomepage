@echo off
chcp 65001 >nul

echo [BAT] 실행됨 >> D:\0.realProject\xampp\htdocs\logs\bat_debug.log
echo 받은 인자: %1 %2 %3 %4 >> D:\0.realProject\xampp\htdocs\logs\bat_debug.log

REM 전체 명령어를 cmd /c 안에 감싸고, 인자들을 이중 따옴표로 정확히 유지
start "" /B cmd /c ""D:\0.realProject\xampp\php\php.exe" "D:\0.realProject\xampp\htdocs\php\auth\send_email.php" %1 %2 %3 %4 >> D:\0.realProject\xampp\htdocs\logs\php_debug.log 2>&1"
