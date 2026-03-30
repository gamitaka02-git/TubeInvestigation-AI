<?php
/**
 * Gemini APIレスポンスを解析し、結果またはエラーメッセージを返す
 */
function parse_gemini_response($response)
{
    if ($response === false) {
        return ['ok' => false, 'msg' => "<p>⚠️ 接続エラー: Gemini APIへの接続に失敗しました。ネットワーク接続を確認してください。</p>"];
    }

    $result = json_decode($response, true);

    if (isset($result['error'])) {
        $code = $result['error']['code'] ?? 0;
        switch ($code) {
            case 429:
                $msg = "レート制限: APIの利用上限に達しました。少し時間を空けてから再試行してください。";
                break;
            case 400:
                $msg = "リクエストエラー: APIキーの設定やリクエスト内容に問題があります。";
                break;
            case 403:
                $msg = "アクセス拒否: APIキーの権限を確認してください。";
                break;
            case 404:
                $msg = "モデル未検出: 指定されたAIモデルが見つかりません。最新の安定モデルに切り替えます。";
                break;
            case 500:
            case 503:
                $msg = "サーバーエラー: Gemini API側で一時的な問題が発生しています。数分待ってから再試行してください。";
                break;
            default:
                $msg = "エラーコード {$code}: " . htmlspecialchars($result['error']['message'] ?? '不明なエラー');
        }
        return ['ok' => false, 'msg' => "<p>⚠️ {$msg}</p>"];
    }

    if (isset($result['candidates'][0]['finishReason']) && $result['candidates'][0]['finishReason'] === 'SAFETY') {
        return ['ok' => false, 'msg' => "<p>⚠️ セーフティブロック: このコンテンツは Google の安全基準により分析できませんでした。</p>"];
    }

    $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
    $usage = $result['usageMetadata'] ?? null;
    if ($text) {
        return ['ok' => true, 'msg' => $text, 'usage' => $usage];
    }

    return ['ok' => false, 'msg' => "<p>⚠️ 分析に失敗しました。予期しないレスポンス形式でした。</p>"];
}

/**
 * トークン利用状況を更新・取得する
 */
function update_token_usage($usage_data)
{
    // configディレクトリが取得できる場合はそこ、そうでなければ現在のディレクトリの1つ上に保存
    $dir = function_exists('get_config_dir') ? get_config_dir() : null;
    $log_file = ($dir ?: __DIR__ . '/..') . '/token_usage.json';
    $current = [];
    
    if (file_exists($log_file)) {
        $current = json_decode(file_get_contents($log_file), true);
    }
    
    if (!isset($current['total_tokens'])) {
        $current = [
            'total_tokens' => 0,
            'prompt_tokens' => 0,
            'candidates_tokens' => 0,
            'request_count' => 0,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    if ($usage_data) {
        $current['total_tokens'] += ($usage_data['totalTokenCount'] ?? 0);
        $current['prompt_tokens'] += ($usage_data['promptTokenCount'] ?? 0);
        $current['candidates_tokens'] += ($usage_data['candidatesTokenCount'] ?? 0);
        $current['request_count']++;
        $current['last_updated'] = date('Y-m-d H:i:s');
        
        file_put_contents($log_file, json_encode($current, JSON_PRETTY_PRINT));
    }
    
    return $current;
}

/**
 * CURLを使用してGemini APIにポストする
 */
function post_to_gemini($api_key, $model, $data)
{
    $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $api_key;
    
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return false;
    }
    return $response;
}

/**
 * Gemini APIを使用してYouTube動画と自分の画像を比較分析する
 */
function analyze_video_summary($api_key, $video_title, $video_description, $model = 'gemini-3.1-flash-lite-preview')
{
    set_time_limit(120);
    
    // 許可されたモデルリスト
    $allowed_models = ['gemini-3.1-flash-lite-preview', 'gemini-3-flash-preview', 'gemini-2.5-flash', 'gemini-2.5-flash-lite'];
    if (!in_array($model, $allowed_models)) {
        $model = 'gemini-3.1-flash-lite-preview';
    }

    $prompt = "あなたはYouTube専門のコンサルタントです。
以下の動画タイトルと説明文から、この動画の内容を分析してください。

【対象動画】
タイトル: {$video_title}
説明文: {$video_description}

【分析項目】
以下の項目について、**HTMLタグ（<h3>, <ul>, <li>, <strong>, <p>など）を使用して**、見やすく整形された日本語で回答してください。
全体のdivタグなどは不要で、中身の要素だけを返してください。

1. <h3>動画の要約</h3>
   - 4行程度で簡潔にまとめてください。

2. <h3>想定されるターゲット視聴者層</h3>
   - この動画に興味を持ちそうな視聴者の具体的な属性や興味関心を挙げてください。

3. <h3>よりユーザーに価値を提供できる動画にするための改善ポイント</h3>
   - 内容・構成・伝え方などの観点から、具体的な改善案を箇条書きで3〜5点挙げてください。

4. <h3>視聴者が得られるベネフィット</h3>
   - この動画を視聴することで視聴者が具体的にどのようなメリットがあるか説明してください。

5. <h3>想定されるキーワード</h3>
   - この動画タイトルから想定されるYouTube SEOに効果的なキーワードを3パターン解説付きで表示してください。";
    
    $data = ["contents" => [["parts" => [["text" => $prompt]]]]];
    $response = post_to_gemini($api_key, $model, $data);
    $parsed = parse_gemini_response($response);
    
    return $parsed['msg'];
}

/**
 * Gemini APIを使用してサムネイル画像を分析する
 */
function analyze_thumbnail($api_key, $thumbnail_url, $video_title, $model = 'gemini-3.1-flash-lite-preview')
{
    set_time_limit(120);

    // 許可されたモデルリスト
    $allowed_models = ['gemini-3.1-flash-lite-preview', 'gemini-3-flash-preview', 'gemini-2.5-flash', 'gemini-2.5-flash-lite'];
    if (!in_array($model, $allowed_models)) {
        $model = 'gemini-3.1-flash-lite-preview';
    }

    // サムネイル画像を取得
    $ch_img = curl_init($thumbnail_url);
    curl_setopt($ch_img, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_img, CURLOPT_SSL_VERIFYPEER, false);
    $image_data = curl_exec($ch_img);
    curl_close($ch_img);

    if (!$image_data) {
        return "<p>サムネイル画像の取得に失敗しました。</p>";
    }
    $base64_image = base64_encode($image_data);

    $prompt = "あなたはYouTubeサムネイルデザインの専門家です。
以下のサムネイル画像を分析してください。動画タイトルは「{$video_title}」です。

【分析項目】
以下の項目について、**HTMLタグ（<h3>, <ul>, <li>, <strong>, <p>など）を使用して**、見やすく整形された日本語で回答してください。
全体のdivタグなどは不要で、中身の要素だけを返してください。

1. <h3>サムネイルの全体的な印象</h3>
   - デザインの第一印象、全体的な雰囲気を2〜3行で述べてください。

2. <h3>色使いとコントラスト</h3>
   - 使用されている主な色、配色の効果、視認性について分析してください。

3. <h3>テキスト・文字の配置</h3>
   - サムネイル内のテキストの有無、フォント、配置の効果を解説してください。テキストがない場合はその旨を述べてください。

4. <h3>効果的なポイント</h3>
   - クリック率を高めるために効果的に使われているテクニックを箇条書きで挙げてください。

5. <h3>より効果的なサムネイルにするための提案</h3>
   - さらにクリック率を向上させるためのアドバイスを具体的に2〜3点挙げてください。";

    $data = ["contents" => [["parts" => [
        ["text" => $prompt],
        ["inline_data" => ["mime_type" => "image/jpeg", "data" => $base64_image]]
    ]]]];

    $response = post_to_gemini($api_key, $model, $data);
    $parsed = parse_gemini_response($response);
    
    return $parsed['msg'];
}