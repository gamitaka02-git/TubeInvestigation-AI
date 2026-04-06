<?php
// ============================================================
// checkout.php - Stripe Checkout セッション作成・リダイレクト
// ============================================================

// stripe-php ライブラリを手動で require
require_once __DIR__ . '/stripe-php/init.php';

// config.php から設定を読み取り（先頭のPHP exitガードを除去してJSONパース）
$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    die('設定ファイルが見つかりません。');
}

$configRaw = file_get_contents($configPath);
// 先頭の PHP exit ガード行を除去
$configJson = preg_replace('/^<\?php\s+exit;\s*\?' . '>\s*/i', '', $configRaw);
$config = json_decode($configJson, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    die('設定ファイルの読み込みに失敗しました。');
}

$stripeSecretKey = $config['stripe_secret_key'] ?? null;
$stripePriceId   = $config['stripe_price_id']   ?? null;

if (!$stripeSecretKey || !$stripePriceId) {
    http_response_code(500);
    die('Stripe の設定が不足しています。config.json を確認してください。');
}

// Stripe API キーを設定
\Stripe\Stripe::setApiKey($stripeSecretKey);

// 現在のホストURL を取得してリダイレクトURLを構築
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$baseUrl  = $protocol . '://' . $host . dirname($_SERVER['SCRIPT_NAME']);

try {
    // Checkout セッションを作成
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price'    => $stripePriceId,
            'quantity' => 1,
        ]],
        'mode'     => 'payment',
        'metadata' => [
            'product' => 'tube_investigation_ai_pro',
        ],
        'success_url' => $baseUrl . '/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'  => $baseUrl . '/index.php',
    ]);

    // Stripe Checkout ページへリダイレクト
    header("Location: " . $session->url);
    exit;

} catch (\Stripe\Exception\ApiErrorException $e) {
    http_response_code(500);
    die('Stripe エラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
} catch (\Exception $e) {
    http_response_code(500);
    die('予期しないエラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
