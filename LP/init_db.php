<?php
// ============================================================
// init_db.php - ti_licenses テーブル初期化スクリプト
// ============================================================
// 使用方法: CLI から実行 → php init_db.php

$dataDir = __DIR__ . '/data';
$dbPath  = $dataDir . '/licenses.db';

// data ディレクトリを作成（存在しなければ）
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
    echo "[OK] data ディレクトリを作成しました: $dataDir\n";
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ti_licenses テーブルを作成
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS ti_licenses (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            email       TEXT    NOT NULL,
            license_key TEXT    NOT NULL UNIQUE,
            status      TEXT    NOT NULL DEFAULT 'active',
            created_at  TEXT    NOT NULL DEFAULT (datetime('now'))
        )
    ");

    echo "[OK] ti_licenses テーブルを作成しました（または既に存在）\n";
    echo "[OK] DB パス: $dbPath\n";

} catch (PDOException $e) {
    echo "[ERROR] DB 初期化失敗: " . $e->getMessage() . "\n";
    exit(1);
}
