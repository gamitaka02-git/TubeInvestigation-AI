#!/bin/bash

# 現在のディレクトリをスクリプトのあるディレクトリに移動
cd "$(dirname "$0")"

# PHPコマンドのパスを探す
PHP_CMD=""

# 1. まずはパッケージ用の php-mac/php を探す
if [ -x "php-mac/php" ]; then
    PHP_CMD="php-mac/php"
    echo "同梱された ./php-mac/php を使用して起動します。"
# 2. 次にシステムの php または Homebrew の php を探す
elif command -v php >/dev/null 2>&1; then
    PHP_CMD="php"
    echo "システムにインストールされている PHP を使用して起動します: $(which php)"
else
    echo "======================================================="
    echo "エラー: Mac上にPHPが見つかりません！！"
    echo ""
    echo "本ツールを動かすにはPHPが必要です。以下のいずれかを行ってください："
    echo "A. ターミナルでHomebrewを使ってインストールする"
    echo "   brew install php"
    echo ""
    echo "B. または、プロジェクトフォルダ内に php-mac/php として"
    echo "   Mac用の静的PHPバイナリを配置してください。"
    echo "======================================================="
    read -p "Enterキーを押して終了します..."
    exit 1
fi

echo "TubeInvestigation AIのローカルサーバーを起動しています..."

# PHPビルトインサーバーを起動 (ドキュメントルートは wwwフォルダ)
$PHP_CMD -S localhost:8000 -t www > /dev/null 2>&1 &
PHP_PID=$!

# ターミナル終了時にPHPサーバーを確実に終了させるためのトラップ
trap 'echo "サーバーを終了しています..."; kill $PHP_PID >/dev/null 2>&1; exit' EXIT HUP INT QUIT TERM

# 起動待ち
sleep 1

# ブラウザを開く
echo "ブラウザで http://localhost:8000 にアクセスします..."
open "http://localhost:8000"

echo ""
echo "======================================================="
echo "起動完了！ブラウザの画面をご確認ください。"
echo "サーバーを終了するには、このターミナルウィンドウを閉じるか"
echo "「Ctrl + C」を押してください。"
echo "======================================================="

# スクリプトが終了しないようにPHPのプロセスを待機
wait $PHP_PID
