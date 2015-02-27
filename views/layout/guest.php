<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= NAME; ?>: <?= $title_for_layout; ?></title>
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>/css/guest/guest.css">

    <link rel="icon" type="image/png" href="<?= BASE_URL; ?>/img/favicon.png">
    <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>
  <body>
    <?= $content_for_layout; ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="<?= BASE_URL; ?>/js/guest/guest.js"></script>
  </body>
</html>