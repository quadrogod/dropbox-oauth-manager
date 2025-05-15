<?php
require_once __DIR__ . "/vendor/autoload.php";
$config = require __DIR__ . "/config.env.php";

$dropboxManager = new \Quadrogod\DropboxManager\DropboxOAuthManager($config);

if (empty($_GET['code'])) {
    $dropboxManager->renderLoginPage();
} else {
    if ($result = $dropboxManager->getAndSaveToken($_GET['code'])) {
        $message = 'The token has been successfully obtained and saved.';
    } else {
        $message = 'Error retrieving the token.';
    }
    //
    $dropboxManager->renderMessagePage($result, $message);
}

/**
 * You can use callback functions. Default actions create json file in $config['token_file'] path
 * $dropboxManager->getAndSaveToken($_GET['code'], function ($token, $response) {
 * var_dump($token, $response);
 * return true;
 * }, function ($response) {
 * var_dump($response);
 * return false;
 * });
 */

