<?php

/**
 * ライセンス認証管理クラス
 * 
 * HWIDの取得と、API経由でのライセンス認証を行います。
 */
class LicenseManager
{
    /**
     * @var string ライセンス認証APIのエンドポイント（プレースホルダー）
     */
    private string $apiUrl = 'https://gamitaka.com/tools/ti-ai/api/check_license.php';

    /**
     * マザーボードのUUIDを取得し、SHA-256でハッシュ化したHWIDを返す
     *
     * @return string HWID
     * @throws RuntimeException UUIDが取得できない場合
     */
    public function getHwid(): string
    {
        $output = [];
        $return_var = -1;
        // Windowsのwmicコマンドを使用してマザーボードのUUIDを取得します
        // 2>&1 で標準エラー出力も取得します
        exec('wmic csproduct get uuid 2>&1', $output, $return_var);

        if ($return_var !== 0 || empty($output)) {
            throw new RuntimeException('マザーボードのUUIDを取得できませんでした。');
        }

        $uuid = '';
        foreach ($output as $line) {
            $line = trim($line);
            // ヘッダ行（'UUID'）をスキップして値を取得
            if (!empty($line) && strtolower($line) !== 'uuid') {
                $uuid = $line;
                break;
            }
        }

        if (empty($uuid)) {
            throw new RuntimeException('有効なUUIDが見つかりませんでした。');
        }

        // 取得したUUIDをSHA-256でハッシュ化して返す
        return hash('sha256', $uuid);
    }

    /**
     * APIサーバーと通信してライセンスを検証する
     *
     * @param string $licenseKey ユーザーのライセンスキー
     * @param string $hwid  ハッシュ化されたHWID
     * @return array 認証結果配列 (['success' => bool, 'message' => string])
     */
    public function authorize(string $licenseKey, string $hwid): array
    {
        if (empty($licenseKey)) {
            return ['success' => false, 'message' => 'ライセンスキーが設定されていません。'];
        }

        if (empty($hwid)) {
            return ['success' => false, 'message' => 'HWIDが取得できていません。'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'action'      => 'activate',
            'license_key' => $licenseKey,
            'hwid'        => $hwid
        ]));
        // 商用利用向けの堅牢なセキュリティ設定（SSL証明書の検証を有効化）
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        // タイムアウト設定（ネットワーク未接続時等で長時間待機させないため）
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // ネットワークエラー、またはタイムアウトなどのCURLエラー
        if ($response === false) {
            return ['success' => false, 'message' => 'ライセンスサーバーに接続できませんでした（通信エラー）: ' . $error];
        }

        // APIからのレスポンスをJSONとしてパース
        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'message' => 'ライセンスサーバーからの不正な応答です（HTTP ' . $httpCode . '）。'];
        }

        // 認証成功時 (API仕様に応じた判定。ここでは status === 'success' と仮定)
        if (isset($result['status']) && $result['status'] === 'success') {
            return ['success' => true, 'message' => 'ライセンスが有効です。'];
        }

        // 認証失敗時
        $errorMessage = $result['message'] ?? '無効なライセンス、またはライセンスの期限が切れています。';
        return ['success' => false, 'message' => $errorMessage];
    }

    /**
     * APIサーバーと通信してこのPCのライセンス認証を解除する
     *
     * @param string $licenseKey ユーザーのライセンスキー
     * @param string $hwid  ハッシュ化されたHWID
     * @return array 解除結果配列 (['success' => bool, 'message' => string])
     */
    public function deauthorize(string $licenseKey, string $hwid): array
    {
        if (empty($licenseKey) || empty($hwid)) {
            return ['success' => false, 'message' => 'ライセンスキーまたはHWIDが設定されていません。'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'action'      => 'deactivate',
            'license_key' => $licenseKey,
            'hwid'        => $hwid
        ]));
        // 商用利用向けの堅牢なセキュリティ設定（SSL証明書の検証を有効化）
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        // タイムアウト設定
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            return ['success' => false, 'message' => 'ライセンスサーバーに接続できませんでした（通信エラー）: ' . $error];
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'message' => 'ライセンスサーバーからの不正な応答です。'];
        }

        if (isset($result['status']) && $result['status'] === 'success') {
            return ['success' => true, 'message' => $result['message'] ?? '認証を解除しました。'];
        }

        $errorMessage = $result['message'] ?? '認証解除に失敗しました。';
        return ['success' => false, 'message' => $errorMessage];
    }
}
