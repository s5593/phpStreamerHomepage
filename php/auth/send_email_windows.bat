@echo off
chcp 65001 >nul
echo bat 실행됨 >> D:\0.realProject\xampp\htdocs\logs\bat_debug.log
echo 받은 인자: %1 %2 >> D:\0.realProject\xampp\htdocs\logs\bat_debug.log
start "" /B "D:\0.realProject\xampp\php\php.exe" "D:\0.realProject\xampp\htdocs\php\auth\send_email.php" %1 %2
