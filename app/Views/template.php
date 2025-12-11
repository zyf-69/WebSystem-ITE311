<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ITE311-DIGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background:#f8f9fa; }
        .navbar { box-shadow: 0 1px 2px rgba(0,0,0,.05); }
        .container-main { max-width: 980px; }
    </style>
</head>
<body>
    <?= $this->include('templates/header') ?>

    <main class="container container-main py-4">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
