<?php
require_once __DIR__ . "/vendor/autoload.php";
$config = require __DIR__ . "/config.env.php";

$dropboxManager = new \Quadrogod\DropboxManager\DropboxOAuthManager($config);

if ($dropboxManager->checkAndRefreshToken()) {
    echo 'The token is valid or has been successfully refreshed.';
} else {
    echo 'Error verifying or refreshing the token.';
}

if ($message = $dropboxManager->getLastMesssage()) {
    echo "<br>{$message}";
}
