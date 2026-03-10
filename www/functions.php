<?php
// 現在のアプリバージョン
define('APP_VERSION', 'v1.0.1');
$config_file = __DIR__ . '/../config.json';

/**
 * 設定の読み込み
 */
function load_config($path)
{
    $defaults = ['license_key' => '', 'api_key' => '', 'gemini_key' => ''];
    if (!file_exists($path)) {
        save_config($path, $defaults);
        return $defaults;
    }
    $config = json_decode(file_get_contents($path), true);
    return array_merge($defaults, $config);
}

/**
 * 設定の保存
 */
function save_config($path, $data)
{
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * GitHub API から最新のリリース情報を取得し、更新が必要か確認する
 *
 * @param bool $force trueの場合は前回のチェック時間を無視して強制チェック
 * @param string $config_file config.jsonのパス
 * @return array|false 更新がある場合は配列 ['has_update' => true, 'latest_version' => 'vX.X.X', 'url' => '...']、ない/エラー時は false を返す
 */
function check_for_updates($force = false, $config_file = null)
{
    $repo_owner = 'gamitaka02-git';
    $repo_name  = 'TubeInvestigation-AI';
    $api_url    = "https://api.github.com/repos/{$repo_owner}/{$repo_name}/releases/latest";
    $cache_time = 7 * 24 * 60 * 60; // 7日間 (604,800秒)

    $config = [];
    if ($config_file && file_exists($config_file)) {
        $config = json_decode(file_get_contents($config_file), true) ?: [];
    }

    $last_check = $config['last_update_check'] ?? 0;
    $now = time();

    // 手動(強制)ではなく、かつ7日経過していない場合はチェックをスキップ
    if (!$force && ($now - $last_check) < $cache_time) {
        return false;
    }

    // GitHub APIリクエスト
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // User-Agent はGitHub APIの必須要件
    curl_setopt($ch, CURLOPT_USERAGENT, 'TubeInvestigation-AI');
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // タイムアウトは短めに
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // API取得に成功した場合のみ最終チェック時刻を更新
    if ($http_code === 200 && $response) {
        if ($config_file) {
            $config['last_update_check'] = $now;
            save_config($config_file, $config);
        }

        $data = json_decode($response, true);
        if (isset($data['tag_name'])) {
            $latest_version = $data['tag_name'];
            
            // 優先順位 1. config.json の短縮URL, 2. GitHubのZipファイル(assets), 3. html_url
            $download_url = $config['update_url'] ?? null;
            if (!$download_url && !empty($data['assets']) && !empty($data['assets'][0]['browser_download_url'])) {
                $download_url = $data['assets'][0]['browser_download_url'];
            }
            if (!$download_url) {
                $download_url = $data['html_url'] ?? "https://github.com/{$repo_owner}/{$repo_name}/releases/latest";
            }

            // バージョン比較 (APP_VERSION と tag_name が異なる、または最新が新しい場合)
            // v1.0.0 のような形式を想定し、version_compareを使用
            $current_ver_num = ltrim(APP_VERSION, 'v');
            $latest_ver_num  = ltrim($latest_version, 'v');

            if (version_compare($current_ver_num, $latest_ver_num, '<')) {
                return [
                    'has_update' => true,
                    'latest_version' => $latest_version,
                    'url' => $download_url
                ];
            }
        }
    }

    // 更新なし、またはAPIエラー
    if ($force) {
        return ['has_update' => false]; // 手動チェック時は「更新なし」を返すため
    }
    return false;
}

/**
 * 入力された文字列からチャンネルIDを特定する
 */
function resolve_channel_id($input, $api_key)
{
    $input = trim($input);
    if (strpos($input, 'UC') === 0 && strlen($input) === 24) return $input;
    $id_param = "";
    if (strpos($input, '@') !== false) {
        $parts = explode('@', $input);
        $handle = explode('/', end($parts))[0];
        $id_param = "forHandle=" . urlencode($handle);
    } elseif (strpos($input, 'youtube.com/channel/') !== false) {
        return explode('channel/', $input)[1];
    } else {
        $search_url = "https://www.googleapis.com/youtube/v3/search?part=snippet&q=" . urlencode($input) . "&type=channel&maxResults=1&key={$api_key}";
        $res = json_decode(@file_get_contents($search_url), true);
        return $res['items'][0]['snippet']['channelId'] ?? null;
    }
    if ($id_param) {
        $url = "https://www.googleapis.com/youtube/v3/channels?part=id&{$id_param}&key={$api_key}";
        $res = json_decode(@file_get_contents($url), true);
        return $res['items'][0]['id'] ?? null;
    }
    return null;
}

function iso8601_to_seconds($iso_duration)
{
    $interval = new DateInterval($iso_duration);
    return ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
}

/**
 * YouTubeデータの取得
 */
function fetch_youtube_data($q, $api_key, $mode = 'keyword', $min_subs = 0, $min_dur = 0, $max_dur = 0, $region_code = 'JP')
{
    $all_items = [];
    $next_page_token = '';
    $channel_meta = null;

    if ($mode === 'trending') {
        for ($i = 0; $i < 2; $i++) {
            $token_param = $next_page_token ? "&pageToken={$next_page_token}" : "";
            $url = "https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics,contentDetails&chart=mostPopular&regionCode={$region_code}&maxResults=50&key={$api_key}{$token_param}";
            $res = json_decode(@file_get_contents($url), true);
            if (!empty($res['items'])) {
                $all_items = array_merge($all_items, $res['items']);
                $next_page_token = $res['nextPageToken'] ?? null;
            }
            if (!$next_page_token) break;
        }
    } else {
        if ($mode === 'channel') {
            $channel_id = resolve_channel_id($q, $api_key);
            if (!$channel_id) return ['items' => []];
            $c_url = "https://www.googleapis.com/youtube/v3/channels?part=snippet,statistics&id={$channel_id}&key={$api_key}";
            $c_res = json_decode(@file_get_contents($c_url), true);
            if (!empty($c_res['items'][0])) {
                $channel_meta = ['title' => $c_res['items'][0]['snippet']['title'], 'subs' => (int)($c_res['items'][0]['statistics']['subscriberCount'] ?? 0)];
            }
        }
        $q_encoded = urlencode($q);
        for ($i = 0; $i < 2; $i++) {
            $token_param = $next_page_token ? "&pageToken={$next_page_token}" : "";
            $url = ($mode === 'channel')
                ? "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId={$channel_id}&type=video&maxResults=50&order=date&key={$api_key}{$token_param}"
                : "https://www.googleapis.com/youtube/v3/search?part=snippet&q={$q_encoded}&type=video&maxResults=50&key={$api_key}{$token_param}";
            $res = json_decode(@file_get_contents($url), true);
            if (!empty($res['items'])) {
                $all_items = array_merge($all_items, $res['items']);
                $next_page_token = $res['nextPageToken'] ?? null;
            }
            if (!$next_page_token) break;
        }
    }

    if (!$all_items) return ['items' => []];

    if ($mode !== 'trending') {
        $video_ids = array_column(array_column($all_items, 'id'), 'videoId');
        $video_details = get_video_details($video_ids, $api_key);
    }

    $channel_ids = array_column(array_column($all_items, 'snippet'), 'channelId');
    $channel_stats = get_channel_stats($channel_ids, $api_key);

    $results = [];
    foreach ($all_items as $item) {
        $v_id = ($mode === 'trending') ? $item['id'] : $item['id']['videoId'];
        $c_id = $item['snippet']['channelId'];

        if ($mode === 'trending') {
            $views = $item['statistics']['viewCount'] ?? 0;
            $likes = $item['statistics']['likeCount'] ?? 0;
            $comments = $item['statistics']['commentCount'] ?? 0;
            $duration = iso8601_to_seconds($item['contentDetails']['duration']);
        } else {
            $views = $video_details[$v_id]['views'] ?? 0;
            $likes = $video_details[$v_id]['likes'] ?? 0;
            $comments = $video_details[$v_id]['comments'] ?? 0;
            $duration = $video_details[$v_id]['duration'] ?? 0;
        }

        $subs = $channel_stats[$c_id] ?? 0;

        if ($subs < $min_subs) continue;
        if ($min_dur > 0 && $duration < ($min_dur * 60)) continue;
        if ($max_dur > 0 && $duration > ($max_dur * 60)) continue;

        $results[] = [
            'title' => $item['snippet']['title'],
            'thumb' => $item['snippet']['thumbnails']['medium']['url'],
            'video_id' => $v_id,
            'channel_title' => $item['snippet']['channelTitle'],
            'views' => (int)$views,
            'likes' => (int)$likes,
            'comments' => (int)$comments,
            'duration' => $duration,
            'subs' => (int)$subs,
            'score' => ($subs > 0) ? round($views / $subs, 2) : 0,
            'published' => $item['snippet']['publishedAt']
        ];
    }
    return ['items' => $results, 'channel_meta' => $channel_meta];
}

function get_video_details($ids, $key)
{
    $details = [];
    foreach (array_chunk($ids, 50) as $chunk) {
        $url = "https://www.googleapis.com/youtube/v3/videos?part=statistics,contentDetails&id=" . implode(',', $chunk) . "&key=$key";
        $data = json_decode(@file_get_contents($url), true);
        if (!empty($data['items'])) {
            foreach ($data['items'] as $v) {
                $details[$v['id']] = [
                    'views' => $v['statistics']['viewCount'] ?? 0,
                    'likes' => $v['statistics']['likeCount'] ?? 0,
                    'comments' => $v['statistics']['commentCount'] ?? 0,
                    'duration' => iso8601_to_seconds($v['contentDetails']['duration'])
                ];
            }
        }
    }
    return $details;
}

function get_channel_stats($ids, $key)
{
    $stats = [];
    foreach (array_chunk(array_unique($ids), 50) as $chunk) {
        $url = "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=" . implode(',', $chunk) . "&key=$key";
        $data = json_decode(@file_get_contents($url), true);
        if (!empty($data['items'])) {
            foreach ($data['items'] as $c) $stats[$c['id']] = $c['statistics']['subscriberCount'] ?? 0;
        }
    }
    return $stats;
}

/**
 * 相対日時のフォーマット
 */
function get_relative_time($datetime)
{
    if (!$datetime) return '';
    $now = new DateTime();
    $target = new DateTime($datetime);
    $diff = $now->diff($target);

    if ($diff->y > 0) return $diff->y . '年前';
    if ($diff->m > 0) return $diff->m . 'ヶ月前';
    if ($diff->d >= 7) return floor($diff->d / 7) . '週間前';
    if ($diff->d > 0) return $diff->d . '日前';
    if ($diff->h > 0) return $diff->h . '時間前';
    if ($diff->i > 0) return $diff->i . '分前';
    return '数秒前';
}

