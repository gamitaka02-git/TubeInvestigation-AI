<?php
require_once 'functions.php';
require_once __DIR__ . '/../src/LicenseManager.php';

$config = load_config($config_file);
$license_key = $config['license_key'] ?? '';
$api_key = $config['api_key'] ?? '';
$gemini_key = $config['gemini_key'] ?? ''; // Geminiキーを読み込み

$licenseManager = new LicenseManager();
$hwid = '';
$hwidError = '';
try {
    $hwid = $licenseManager->getHwid();
} catch (Exception $e) {
    $hwidError = $e->getMessage();
}

$is_licensed = false;
$auth_error = '';
if ($license_key && $hwid) {
    $authResult = $licenseManager->authorize($license_key, $hwid);
    $is_licensed = $authResult['success'];
    if (!$is_licensed) {
        $auth_error = $authResult['message'];
    }
} elseif (!$license_key) {
    $auth_error = ''; // 初期状態や未設定時は単に入力を促す
} elseif ($hwidError) {
    $auth_error = 'システムエラー: ' . $hwidError;
}

// 設定保存処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_key'])) {
    save_config($config_file, [
        'license_key' => trim($_POST['license_key']),
        'api_key' => trim($_POST['api_key']),
        'gemini_key' => trim($_POST['gemini_key']) // Geminiキーも保存
    ]);
    header('Location: ./');
    exit;
}

$mode = $_GET['mode'] ?? 'keyword';
$q = $_GET['q'] ?? '';
$sort_by = $_GET['sort'] ?? 'score';
$min_subs = isset($_GET['min_subs']) ? (int) $_GET['min_subs'] : 0;
$min_dur = isset($_GET['min_dur']) ? (int) $_GET['min_dur'] : 0;
$max_dur = isset($_GET['max_dur']) ? (int) $_GET['max_dur'] : 0;
$region = $_GET['region'] ?? 'JP';

$regions = ['JP' => '🇯🇵 日本', 'US' => '🇺🇸 アメリカ', 'KR' => '🇰🇷 韓国', 'GB' => '🇬🇧 イギリス', 'BR' => '🇧🇷 ブラジル', 'IN' => '🇮🇳 インド'];

$search_results = [];
if ($api_key && ($mode === 'trending' || $q !== '')) {
    $data = fetch_youtube_data($q, $api_key, $mode, $min_subs, $min_dur, $max_dur, $region);
    $search_results = $data['items'] ?? [];
    $channel_meta = $data['channel_meta'] ?? null;
    usort($search_results, function ($a, $b) use ($sort_by) {
        return $b[$sort_by] <=> $a[$sort_by];
    });
}

// CSVダウンロード
if (isset($_GET['download']) && $_GET['download'] === 'csv' && !empty($search_results)) {
    $filename = "youtube_research_" . date('Ymd_His') . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF");
    fputcsv($output, ['タイトル', 'チャンネル名', '再生数', '高評価', 'コメント数', '登録者数', 'バズり度', '動画時間(秒)', 'URL', '投稿日時']);
    foreach ($search_results as $res) {
        fputcsv($output, [$res['title'], $res['channel_title'], $res['views'], $res['likes'], $res['comments'], $res['subs'], $res['score'], $res['duration'], "https://www.youtube.com/watch?v=" . $res['video_id'], $res['published']]);
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>TubeInvestigation AI</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container container-wide">
        <?php if (!$is_licensed || !$api_key || !$gemini_key || isset($_GET['edit_key'])): ?>
            <div class="overlay">
                <div class="modal">
                    <h2>API & ライセンス設定</h2>
                    <?php if ($auth_error && !isset($_GET['edit_key'])): ?>
                        <div style="color: #d32f2f; background: #ffebee; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-weight: bold;">
                            <?php echo htmlspecialchars($auth_error); ?>
                        </div>
                    <?php endif; ?>
                    <div class="setup-guide">
                        <p>ツールを動作させるにはライセンスキーと2つのAPIキーが必要です。</p>
                        <div><a href="https://console.cloud.google.com/welcome" target="_blank" class="btn-guide">YouTube
                                API ↗</a>　（Google Cloud Console）</div>
                        <div><a href="https://aistudio.google.com/app/apikey" target="_blank" class="btn-guide"
                                style="margin-top:5px; border-color:#8e24aa; color:#8e24aa;">Gemini API ↗</a>　（Google AI
                            Studio）</div>
                    </div>
                    <hr class="modal-divider">
                    <form method="POST">
                        <div class="api-input">
                            <label>License Key</label>
                            <input type="password" name="license_key" value="<?php echo htmlspecialchars($license_key); ?>"
                                placeholder="ライセンスキーを入力" required>
                        </div>

                        <div class="api-input">
                            <label>YouTube API Key</label>
                            <input type="password" name="api_key" value="<?php echo htmlspecialchars($api_key); ?>"
                                placeholder="YouTube APIキーを入力" required>
                        </div>

                        <div class="api-input">
                            <label>Gemini API Key</label><input type="password" name="gemini_key"
                                value="<?php echo htmlspecialchars($gemini_key); ?>" placeholder="Gemini APIキーを入力" required>
                        </div>

                        <button type="submit" name="save_key" class="btn-save">設定を保存して開始</button>
                        <?php if (isset($_GET['edit_key'])): ?><a href="./" class="btn-cancel">キャンセル</a><?php endif; ?>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <header class="header">
            <h1>TubeInvestigation AI</h1><a href="?edit_key=1" class="btn-settings">キーを変更する</a>
        </header>

        <form method="GET" class="search-form">
            <div class="mode-selector">
                <label class="mode-label"><input type="radio" name="mode" value="keyword" <?php echo $mode === 'keyword' ? 'checked' : ''; ?>><span>🔍 キーワード</span></label>
                <label class="mode-label"><input type="radio" name="mode" value="channel" <?php echo $mode === 'channel' ? 'checked' : ''; ?>><span>📺 チャンネル</span></label>
                <label class="mode-label"><input type="radio" name="mode" value="trending" <?php echo $mode === 'trending' ? 'checked' : ''; ?>><span>🔥 急上昇</span></label>
                <div class="filter-group"><span>地域:</span><select
                        name="region"><?php foreach ($regions as $code => $name): ?>
                            <option value="<?php echo $code; ?>" <?php echo $region === $code ? 'selected' : ''; ?>>
                                <?php echo $name; ?></option><?php endforeach; ?>
                    </select></div>
                <div class="filter-group"><span>最低登録者:</span><input type="number" name="min_subs"
                        value="<?php echo $min_subs; ?>" min="0"></div>
                <div class="filter-group time"><span>分:</span><input type="number" name="min_dur"
                        value="<?php echo $min_dur; ?>" min="0"><span>〜</span><input type="number" name="max_dur"
                        value="<?php echo $max_dur; ?>" min="0"></div>
            </div>
            <div class="keyword-form">
                <input type="text" id="search-input" name="q" value="<?php echo htmlspecialchars($q); ?>"
                    style="flex-grow: 1;">
                <button type="submit">リサーチ開始</button>
            </div>
        </form>

        <?php if ($search_results): ?>
            <?php if ($mode === 'channel' && !empty($channel_meta)): ?>
                <div class="channel-info-box">
                    <strong>📺 <?php echo htmlspecialchars($channel_meta['title']); ?></strong>
                    <span>｜登録者数: <?php echo number_format($channel_meta['subs']); ?> 人</span>
                </div>
            <?php endif; ?>
            <div class="sort-container">
                <div>
                    <span>並び替え：</span>
                    <?php $url_params = "q=" . urlencode($q) . "&mode=$mode&min_subs=$min_subs&min_dur=$min_dur&max_dur=$max_dur&region=$region"; ?>
                    <a href="?<?php echo $url_params; ?>&sort=score"
                        class="btn-sort <?php echo $sort_by == 'score' ? 'active' : ''; ?>">🔥 バズり度</a>
                    <a href="?<?php echo $url_params; ?>&sort=views"
                        class="btn-sort <?php echo $sort_by == 'views' ? 'active' : ''; ?>">👁 再生数</a>
                    <a href="?<?php echo $url_params; ?>&sort=likes"
                        class="btn-sort <?php echo $sort_by == 'likes' ? 'active' : ''; ?>">👍 高評価</a>
                    <a href="?<?php echo $url_params; ?>&sort=comments"
                        class="btn-sort <?php echo $sort_by == 'comments' ? 'active' : ''; ?>">💬 コメント数</a>
                    <?php if ($mode !== 'channel'): ?><a href="?<?php echo $url_params; ?>&sort=subs"
                            class="btn-sort <?php echo $sort_by == 'subs' ? 'active' : ''; ?>">👥 登録者数</a><?php endif; ?>
                </div>
                <a href="?<?php echo $url_params; ?>&sort=<?php echo $sort_by; ?>&download=csv" class="btn-save btn-csv">📥
                    CSV保存</a>
            </div>

            <div class="table-wrapper">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th class="col-thumb">サムネイル</th>
                            <th class="col-title">タイトル / チャンネル</th>
                            <th class="col-views">再生数</th>
                            <th class="col-likes">高評価</th>
                            <th class="col-comments">コメント数</th>
                            <?php if ($mode !== 'channel'): ?>
                                <th class="col-subs">登録者数</th><?php endif; ?>
                            <th class="col-score">バズり度</th>
                            <th class="col-action">分析</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($search_results as $res): ?>
                            <?php $v_url = "https://www.youtube.com/watch?v=" . $res['video_id']; ?>
                            <tr>
                                <td class="col-thumb"><a href="<?php echo $v_url; ?>" target="_blank"><img
                                            src="<?php echo $res['thumb']; ?>" class="thumb-img"></a></td>
                                <td class="col-title">
                                    <div class="video-title"><a href="<?php echo $v_url; ?>" target="_blank"
                                            class="video-link"><?php echo htmlspecialchars($res['title']); ?></a></div>
                                    <div class="channel-meta"><?php echo get_relative_time($res['published']); ?> • <?php echo htmlspecialchars($res['channel_title']); ?> <span
                                            class="video-duration">(<?php echo floor($res['duration'] / 60); ?>:<?php echo sprintf('%02d', $res['duration'] % 60); ?>)</span>
                                    </div>
                                </td>
                                <td class="col-views"><span><?php echo number_format($res['views']); ?></span><span
                                        class="unit-label"> 回</span></td>
                                <td class="col-likes"><span><?php echo number_format($res['likes']); ?></span></td>
                                <td class="col-comments"><span><?php echo number_format($res['comments']); ?></span></td>
                                <?php if ($mode !== 'channel'): ?>
                                    <td class="col-subs"><span><?php echo number_format($res['subs']); ?></span><span
                                            class="unit-label"> 人</span></td><?php endif; ?>
                                <td class="col-score"><span
                                        class="score-value <?php echo $res['score'] >= 20 ? 'high-score' : ''; ?>"><?php echo $res['score']; ?></span><span
                                        class="unit-label"> 倍</span></td>
                                <td class="col-action"><a href="analysis.php?v=<?php echo $res['video_id']; ?>"
                                        class="btn-analysis" target="_blank">🔍 AI分析</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <footer class="footer">
            <p>&copy; TubeInvestigation AI</p>
        </footer>
    </div>
    <script src="script.js"></script>
</body>

</html>