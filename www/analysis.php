<?php
require_once 'functions.php';
require_once 'functions_ai.php';
require_once __DIR__ . '/../src/LicenseManager.php';

$config = load_config($config_file);
$license_key = $config['license_key'] ?? '';
$yt_key = $config['api_key'] ?? '';
$gemini_key = $config['gemini_key'] ?? '';
$gemini_model = $config['gemini_model'] ?? 'gemini-3.1-flash-lite-preview';

$licenseManager = new LicenseManager();
$hwid = '';
try {
    $hwid = $licenseManager->getHwid();
} catch (Exception $e) {
    die("システムエラー: " . $e->getMessage());
}

if (!$license_key || !$hwid || !$licenseManager->authorize($license_key, $hwid)['success']) {
    die("ライセンスが無効です。メイン画面から設定を確認してください。");
}

$video_id = $_GET['v'] ?? '';
if (!$video_id)
    die("動画IDが不足しています。");

// YouTube詳細取得
$url = "https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics&id={$video_id}&key={$yt_key}";
$video_data = json_decode(file_get_contents($url), true)['items'][0] ?? null;
if (!$video_data)
    die("動画が見つかりません。");

$title = $video_data['snippet']['title'];
$channelTitle = $video_data['snippet']['channelTitle'] ?? '';
$yt_thumb = $video_data['snippet']['thumbnails']['maxres']['url'] ?? $video_data['snippet']['thumbnails']['medium']['url'];
$description = $video_data['snippet']['description'] ?? '';
$views = (int)($video_data['statistics']['viewCount'] ?? 0);
$likes = (int)($video_data['statistics']['likeCount'] ?? 0);
$comments = (int)($video_data['statistics']['commentCount'] ?? 0);

// チャンネル詳細（登録者数）取得
$channel_id = $video_data['snippet']['channelId'] ?? '';
$channel_subs = 0;
if ($channel_id && $yt_key) {
    $channel_stats = get_channel_stats([$channel_id], $yt_key);
    $channel_subs = (int)($channel_stats[$channel_id] ?? 0);
}

// AJAXリクエスト処理 (AI分析実行)
if (isset($_GET['action']) && $_GET['action'] === 'analyze_ai') {
    if ($gemini_key) {
        $analysis_result = analyze_video_summary($gemini_key, $title, $description, $gemini_model);
        echo $analysis_result;
    } else {
        echo "<p>Gemini APIキーが設定されていません。</p>";
    }
    exit;
}

// AJAXリクエスト処理 (サムネイル分析実行)
if (isset($_GET['action']) && $_GET['action'] === 'analyze_thumb') {
    if ($gemini_key) {
        $thumb_result = analyze_thumbnail($gemini_key, $yt_thumb, $title, $gemini_model);
        echo $thumb_result;
    } else {
        echo "<p>Gemini APIキーが設定されていません。</p>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>AI動画分析 - TubeInvestigation AI</title>
    <link rel="icon" href="tubeinvestigation_ai.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <header class="header">
            <h1>📝 AI動画分析</h1>
            <div class="header-actions">
                <button class="btn-export btn-export-pdf hidden" id="btn-pdf-header">📄 PDF保存</button>
                <button class="btn-export btn-export-md hidden" id="btn-md-header">📝 Markdown保存</button>
                <a href="javascript:void(0);" class="btn-settings" onclick="window.close(); return false;">ページを閉じる</a>
            </div>
        </header>

        <div class="video-summary-container">
            <div class="video-thumb-large">
                <a href="https://www.youtube.com/watch?v=<?php echo htmlspecialchars($video_id); ?>" target="_blank">
                    <img src="<?php echo $yt_thumb; ?>">
                </a>
                <a href="https://www.youtube.com/watch?v=<?php echo htmlspecialchars($video_id); ?>" target="_blank" class="btn-watch">▶ YouTubeで動画を見る</a>
            </div>
            <div class="video-details">
                <h2 class="analysis-video-title"><?php echo htmlspecialchars($title); ?></h2>
                <div class="analysis-channel-info">
                    ｜<?php echo htmlspecialchars($channelTitle); ?> 
                    <span class="analysis-subs-count">登録者数 <?php echo number_format($channel_subs); ?>人</span>
                </div>
                <div class="video-stats-bar">
                    <span class="stat-badge stat-views">👁 再生数: <?php echo number_format($views); ?></span>
                    <span class="stat-badge stat-likes">👍 高評価: <?php echo number_format($likes); ?></span>
                    <span class="stat-badge stat-comments">💬 コメント: <?php echo number_format($comments); ?></span>
                </div>
                <div class="video-description-box">
                    <?php echo nl2br(htmlspecialchars($description)); ?>
                </div>
            </div>
        </div>

        <?php if ($gemini_key): ?>
            <div class="ai-output">
                <h2 class="ai-title">🤖 AIによる分析結果</h2>

                <div id="ai-loading" class="loading-container">
                    <div class="loader"></div>
                    <div class="loading-text">分析中...</div>
                </div>

                <div id="ai-result" class="ai-content hidden"></div>
            </div>

            <div class="ai-output">
                <h2 class="ai-title">🖼️ サムネイル分析</h2>

                <div id="thumb-loading" class="loading-container">
                    <div class="loader"></div>
                    <div class="loading-text">サムネイルを分析中...</div>
                </div>

                <div id="thumb-result" class="ai-content hidden"></div>
            </div>

            <div class="export-bar hidden" id="export-bar">
                <button class="btn-export btn-export-pdf" id="btn-pdf">📄 PDF保存</button>
                <button class="btn-export btn-export-md" id="btn-md">📝 Markdown保存</button>
            </div>
        <?php else: ?>
            <div class="ai-output">
                <p>Gemini APIキーを設定してください。</p>
            </div>
        <?php endif; ?>

        <footer class="footer">
            <p>&copy; TubeInvestigation AI</p>
        </footer>
    </div>

    <div id="video-meta" class="hidden"
        data-id="<?php echo htmlspecialchars($video_id); ?>"
        data-title="<?php echo htmlspecialchars($title); ?>"
        data-channel="<?php echo htmlspecialchars($channelTitle); ?>"
        data-views="<?php echo $views; ?>"
        data-likes="<?php echo $likes; ?>"
        data-comments="<?php echo $comments; ?>"
        data-thumb="<?php echo htmlspecialchars($yt_thumb); ?>">
    </div>
    <script src="script.js"></script>
</body>

</html>