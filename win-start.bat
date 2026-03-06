@echo off
set PHP_EXE=%~dp0php\php.exe
set PHP_INI=%~dp0php.ini
set WWW_DIR=%~dp0www
set CACERT=%~dp0php\ext\cacert.pem

echo YouTubeリサーチツールを起動しています...

:: 1. ブラウザを起動
start http://localhost:8000

:: 2. PHPのビルトインサーバーを起動 (-c オプションで php.ini を指定, -d で証明書パスを絶対パスに上書き)
"%PHP_EXE%" -c "%PHP_INI%" -d curl.cainfo="%CACERT%" -d openssl.cafile="%CACERT%" -S localhost:8000 -t "%WWW_DIR%"

pause