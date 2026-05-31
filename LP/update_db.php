<?php
// ============================================================
// update_db.php - Stripe連携用カラム追加マイグレーション (堅牢版)
// ============================================================

ini_set('display_errors', 1);
error_reporting(E_ALL);

$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    die('[ERROR] 設定ファイルが見つかりません。');
}

$configRaw = file_get_contents($configPath);
$pattern = '/^<\?php\s+exit;\s*\?' . '>\s*/i';
$configJson = preg_replace($pattern, '', $configRaw);
$config = json_decode($configJson, true);

if (!$config) {
    die('[ERROR] 設定ファイルのパースに失敗しました。');
}

$db_config = [
    'host' => $config['db_host'],
    'dbname' => $config['db_name'],
    'user' => $config['db_user'],
    'pass' => $config['db_pass']
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4",
        $db_config['user'],
        $db_config['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "[INFO] データベースに接続しました。<br>";

    // カラムが存在するかチェックし、なければ追加する関数
    function addColumnIfNeeded($pdo, $tableName, $columnName, $columnType) {
        $stmt = $pdo->query("SHOW COLUMNS FROM `{$tableName}` LIKE '{$columnName}'");
        $exists = $stmt->fetch();

        if (!$exists) {
            // エラーを特定しやすくするために直接実行して結果を表示
            try {
                $pdo->exec("ALTER TABLE `{$tableName}` ADD `{$columnName}` {$columnType}");
                echo "<span style='color:green;'>[OK] `{$tableName}` テーブルに `{$columnName}` カラムを追加しました。</span><br>";
            } catch (PDOException $e) {
                echo "<span style='color:red;'>[ERROR] `{$columnName}` の追加に失敗: " . $e->getMessage() . "</span><br>";
            }
        } else {
            echo "[INFO] `{$tableName}` テーブルの `{$columnName}` カラムは既に存在します。<br>";
        }
    }

    // 不要な user_mail カラムが存在すれば自動で削除（クリーンアップ）
    $stmt = $pdo->query("SHOW COLUMNS FROM `ti_licenses` LIKE 'user_mail'");
    if ($stmt->fetch()) {
        $pdo->exec("ALTER TABLE `ti_licenses` DROP COLUMN `user_mail`");
        echo "<span style='color:orange;'>[CLEANUP] 不要な `user_mail` カラムを削除しました。</span><br>";
    }

    // 1. メールアドレス用のカラム（user_email）が存在するか確認し、なければ追加
    addColumnIfNeeded($pdo, 'ti_licenses', 'user_email', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `id`");

    // 2. Stripe関連カラムを追加（user_email の直後に追加）
    addColumnIfNeeded($pdo, 'ti_licenses', 'stripe_customer_id', "VARCHAR(255) NULL AFTER `user_email`");
    addColumnIfNeeded($pdo, 'ti_licenses', 'stripe_subscription_id', "VARCHAR(255) NULL AFTER `stripe_customer_id`");
    
    // 3. その他の必須カラムを追加
    addColumnIfNeeded($pdo, 'ti_licenses', 'hwid', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `license_key`");
    addColumnIfNeeded($pdo, 'ti_licenses', 'hwid2', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `hwid`");
    addColumnIfNeeded($pdo, 'ti_licenses', 'last_verified_at', "DATETIME NULL AFTER `hwid2`");
    addColumnIfNeeded($pdo, 'ti_licenses', 'plan_type', "VARCHAR(50) NOT NULL DEFAULT 'pro' AFTER `status`");
    addColumnIfNeeded($pdo, 'ti_licenses', 'created_at', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");

    echo "<br><b>[SUCCESS] データベースマイグレーション処理が完了しました。</b><br>";

} catch (PDOException $e) {
    die("[ERROR] DB接続または処理失敗: " . $e->getMessage() . "<br>");
}
