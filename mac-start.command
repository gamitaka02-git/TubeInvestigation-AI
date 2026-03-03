#!/bin/bash

# スクリプトのあるディレクトリに移動
cd "$(dirname "$0")"

echo "YouTube Research Tool を起動しています..."
echo "ブラウザで http://localhost:8000 を開きます..."

# PHPビルトインサーバーをバックグラウンドで起動
# wwwディレクトリをドキュメントルートにする
php -S localhost:8000 -t www &
PHP_PID=$!

# 少し待ってからブラウザを開く
sleep 2
open "http://localhost:8000"

echo "---------------------------------------------------"
echo "ツール起動中！"
echo "終了するにはこのウィンドウを閉じてください（Ctrl+C でも終了できます）"
echo "---------------------------------------------------"

# サーバープロセスが終了するのを待つ
wait $PHP_PID
