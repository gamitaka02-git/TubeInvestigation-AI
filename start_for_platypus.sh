#!/bin/bash

# ======================================================================
# このスクリプトは Platypus で Mac 用の .app を作成するための起動スクリプトです
# Platypus の Text View や Web View などで起動した場合、裏側でPHPを走らせます
# ======================================================================

# アプリのバンドル内のリソースディレクトリに移動
cd "$(dirname "$0")"

# バンドルしたMac用PHPのパス
PHP_MAC_BIN="./php-mac/php"

# 万が一配布時に権限が落ちていた場合に備えて実行権限を付与
chmod +x "$PHP_MAC_BIN" 2>/dev/null

if [ ! -x "$PHP_MAC_BIN" ]; then
    # PHP本体が見つからない場合はMac標準のエラーダイアログを出して終了
    osascript -e 'display alert "エラー: Mac用PHPサーバーが見つかりません" message "アプリケーション内部に \"php-mac/php\" が見つからないため起動できません。"'
    exit 1
fi

# バックグラウンドでPHPローカルサーバーを立ち上げる (ポート8000使用)
"$PHP_MAC_BIN" -S localhost:8000 -t ./www > /dev/null 2>&1 &
PHP_PID=$!

# アプリが終了した時（Dockから「終了」を選んだり、ウィンドウを閉じたりした時）に
# PHPサーバープロセスも確実に終了処理させるためのシグナルトラップ
trap 'kill $PHP_PID >/dev/null 2>&1; exit' EXIT HUP INT QUIT TERM

# サーバー側が立ち上がるまで少し待機する
sleep 1

# MacのデフォルトブラウザでURLを開く
open "http://localhost:8000"

# Platypusがスクリプト完了で終了してしまわないように、PHPのプロセスが終了するまで待機する
# ※Platypus側で「Remain running after execution」などを設定する必要があります
wait $PHP_PID
