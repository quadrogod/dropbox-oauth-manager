<?php

namespace Quadrogod\DropboxManager;

class DropboxOAuthManager
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $tokenFile;
    private $tokenBufferSeconds = 3600;
    private $lastMessage;

    public function __construct($config)
    {
        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->redirectUri = $config['redirect_uri'];
        $this->tokenFile = $config['token_file'];
        $this->tokenBufferSeconds = $config['buffer_seconds'] ?? $this->tokenBufferSeconds;
    }

    public function renderLoginPage()
    {
        $authUrl = $this->getAuthUrl();
        include __DIR__ . '/html/login.php';
        exit;
    }

    public function renderMessagePage($result, $message)
    {
        include __DIR__ . '/html/message.php';
        exit;
    }

    public function getAuthUrl()
    {
        return sprintf(
            'https://www.dropbox.com/oauth2/authorize?client_id=%s&response_type=code&redirect_uri=%s&token_access_type=offline',
            urlencode($this->clientId),
            urlencode($this->redirectUri)
        );
    }

    public function getAndSaveToken($code, Callable $onSuccess = null, Callable $onError = null)
    {
        $data = [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
        ];

        $response = $this->httpPost('https://api.dropboxapi.com/oauth2/token', $data);
        $tokens = json_decode($response, true);

        if (isset($tokens['access_token'], $tokens['refresh_token'])) {
            if ($onSuccess) {
                return call_user_func($onSuccess, $tokens, $response);
            } else {
                file_put_contents($this->tokenFile, json_encode($tokens, JSON_PRETTY_PRINT));
                return true;
            }
        }

        if ($onError) {
            return call_user_func($onError, $response);
        } else {
            return false;
        }
    }

    public function checkAndRefreshToken(callable $onRefreshSuccess = null, callable $onRefreshError = null)
    {
        if (!file_exists($this->tokenFile)) {
            $this->setLastMesssage('Token File Not Found.');
            return false;
        }

        $tokenData = json_decode(file_get_contents($this->tokenFile), true);
        if (!isset($tokenData['refresh_token'])) {
            $this->setLastMesssage('refresh_token Not Found.');
            return false;
        }

        if (isset($tokenData['expires_in'])) {
            $createdAt = filemtime($this->tokenFile);
            $expiresAt = $createdAt + $tokenData['expires_in'];
            if (time() < ($expiresAt - $this->tokenBufferSeconds)) {
                $this->setLastMesssage(
                    'Token update not required. Verification time: ' . date('Y-m-d H:i:s') . ';'
                    . '<br>Expires at: ' . date('Y-m-d H:i:s', $expiresAt)
                    . '<br>Last refreshed at: ' . date('Y-m-d H:i:s', $createdAt)
                    . '<br>Token lifetime: ' . $tokenData['expires_in'] . ' seconds.'
                );
                return true;
            }
        }

        return $this->refreshToken($tokenData['refresh_token'], $onRefreshSuccess, $onRefreshError);
    }

    public function refreshToken($refreshToken, callable $onSuccess = null, callable $onError = null)
    {
        $data = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        $response = $this->httpPost('https://api.dropboxapi.com/oauth2/token', $data);
        $newToken = json_decode($response, true);

        if (isset($newToken['access_token'])) {
            $tokens = $oldData = json_decode(file_get_contents($this->tokenFile), true);
            foreach ($tokens as $key => $value) {
                if (isset($newToken[$key])) {
                    $tokens[$key] = $newToken[$key];
                }
            }

            if ($onSuccess) {
                return call_user_func($onSuccess, $newToken, $oldData);
            } else {
                file_put_contents($this->tokenFile, json_encode($tokens, JSON_PRETTY_PRINT));
                return true;
            }
        }

        if ($onError) {
            return call_user_func($onError, $response);
        } else {
            $this->setLastMesssage('Not Found access_token.');
            return false;
        }
    }

    private function httpPost($url, $postData)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $this->setLastMesssage('Error cURL: ' . curl_error($ch));
        }
        curl_close($ch);
        return $response;
    }

    function getLastMesssage()
    {
        return $this->lastMessage;
    }

    function setLastMesssage($message)
    {
        $this->lastMessage = $message;
    }

}
