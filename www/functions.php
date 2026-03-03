<?php
$config_file = __DIR__ . '/../config.json';

/**
 * 設定の読み込み
 */
function load_config($path)
{
    $defaults = ['api_key' => '', 'gemini_key' => ''];
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

