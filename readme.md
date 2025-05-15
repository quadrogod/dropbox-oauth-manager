# Dropbox OAuth Manager

This is a simple PHP package for authenticating with the **Dropbox OAuth service** to obtain `access_token`, `refresh_token`, and automatically refresh the `access_token` when a valid `refresh_token` is present.

---

## Install

```bash
composer require quadrogod/dropbox-oauth-manager
```

---

## Usage

```php
use Quadrogod\DropboxManager\DropboxOAuthManager;

$config = [
    'client_id' => '', // App key
    'client_secret' => '', // App secret
    'redirect_uri' => '', // Your redirect URI (must be set in Dropbox App settings)
    'token_file' => __DIR__ . "/token.json", // Path to save token data
];

$dropboxManager = new DropboxOAuthManager($config);
$dropboxManager->renderLoginPage();
```

Once you receive the `CODE` from Dropbox:

```php
$dropboxManager->getAndSaveToken($code);
```

This will save the token data to the default `$config['token_file']` path.

You can also use callback functions to handle and save token data according to your own logic:

```php
$dropboxManager->getAndSaveToken($code, function ($token, $response) {
    var_dump($token, $response);
}, function ($response) {
    var_dump($response);
});
```

> More examples can be found in the `example` folder.

---

## Example Project

The `example/` folder contains a fully working demo of how to use this package.

To simplify testing, a `Docker` environment is included. To start the example:

```bash
cd example && docker-compose up -d
```

Then open [http://localhost](http://localhost) in your browser.

You must first fill in the file `example/config.env.php` with your own app credentials.

To create a Dropbox App, visit the [Dropbox Developer Console](https://www.dropbox.com/developers/) and create a new app.  
Don’t forget to enable the necessary **permissions** for your app.

---

## ⏱ Cron Task

Dropbox now issues short-lived `access_token`s valid for **4 hours** to enhance account security.  
You must **automatically refresh** the token before it expires.

The recommended way is to configure a `cron` job that runs hourly:

```cron
0 * * * * php cron.php >/dev/null 2>&1
```

This will ensure your token is always up to date.

> Note: Currently, the token data is loaded from the default file. Passing external `$tokenData` is not yet supported but will be added in future releases.

---

## 🧭 Roadmap

- Refactor and improve the codebase
- Make `DropboxOAuthManager` more universal and reusable
- Improve token management logic
- Add better exception handling and logging

---

## ☕ Support Me

If you like this project and want to support further development:  
[https://buymeacoffee.com/quadrogod](https://buymeacoffee.com/quadrogod)

[![Buy Me A Coffee](https://img.shields.io/badge/-Buy%20me%20a%20coffee-%23ffdd00?logo=buymeacoffee&logoColor=black&style=flat)](https://buymeacoffee.com/quadrogod)
---

MIT License © Alex Molchanov
