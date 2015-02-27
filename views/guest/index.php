<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= NAME; ?>: Bienvenue</title>

    <link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>/css/guest/index.css?<?= time(); ?>">
    <link rel="icon" type="image/png" href="<?= BASE_URL; ?>/img/favicon.png">

    <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>
  <body>
    <div class="container">
      <div class="logo"></div>
    </div>
    <div class="choose_actions ">
      <ul>
        <a href="<?= BASE_URL; ?>/guest/login" title="Connexion"><li>
          <div id="login">
            Connexion
          </div>
        </li></a>
        <a href="<?= BASE_URL; ?>/guest/register" title="Inscription"><li>
          <div id="register">
            Inscription
          </div>
        </li></a>
      </ul>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="<?= BASE_URL; ?>/js/guest/index.js"></script>
  </body>
</html>