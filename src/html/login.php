<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dropbox OAuth Login Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="px-4 py-5 my-5 text-center">
            <img class="d-block mx-auto mb-4" src="/assets/dropbox-official.svg" alt="" height="80">
            <div class="col-lg-6 mx-auto">
                <p class="lead mb-4">A simple example of authentication and token retrieval for Dropbox, with support for automatic token refreshing using a refresh token for smooth backend operation.</p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="<?= $authUrl ?>" class="btn btn-primary btn-lg px-4 gap-3" target="_self">LogIn</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
