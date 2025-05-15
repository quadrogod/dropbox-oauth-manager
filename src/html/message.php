<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dropbox OAuth Login Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container">
        <div class="px-4 py-5 my-5 text-center">
            <?php if($result): ?>
                <i class="bi bi-check2-circle" style="font-size: 4rem; color: #0061ff;"></i>
            <?php else: ?>
                <i class="bi bi-x-circle" style="font-size: 4rem; color: red;"></i>
            <?php endif; ?>

            <h1 class="display-5 fw-bold text-body-emphasis"><?= $result ? 'Success' : 'Failure'?></h1>
            <div class="col-lg-6 mx-auto">
                <p class="lead mb-4"><?= $message ?></p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="/" class="btn btn-outline-secondary btn-lg px-4 gap-3" target="_self">Close</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
