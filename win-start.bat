@echo off
set PHP_EXE=%~dp0php\php.exe
set PHP_INI=%~dp0php\php.ini
set WWW_DIR=%~dp0www

echo YouTubeリサーチツールを起動しています...

:: 1. ブラウザを起動
start http://localhost:8000

:: 2. PHPのビルトインサーバーを起動 (-c オプションで php.ini を指定)
"%PHP_EXE%" -c "%PHP_INI%" -S localhost:8000 -t "%WWW_DIR%"

pause