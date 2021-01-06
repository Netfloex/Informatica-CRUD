<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/uikit.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body class="<?= $doc ?>">
    <?php
    include jPath("elements/navbar.php");
    include $document;
    ?>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/now-ui-kit.min.js"></script>

    <script src="/js/uikit.js"></script>

    <script src="/js/uikit-icons.js"></script>
</body>

</html>