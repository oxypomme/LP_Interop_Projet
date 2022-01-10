<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/php/Location.php';

// Serving static files
if (strpos($_SERVER['REQUEST_URI'], '/static') === 0) {
  $matches = [];
  preg_match('/.*\.(.*)$/', $_SERVER['REQUEST_URI'], $matches);
  $type = 'text/plain';
  switch ($matches[1]) {
    case 'css':
      $type = 'text/css';
      break;
    case 'mjs':
    case 'js':
      $type = 'application/javascript';
      break;

    default:
      break;
  }
  header('Content-Type: ' . $type);
  exit(file_get_contents(__DIR__ . $_SERVER['REQUEST_URI']));
}

// Getting info
try {
  $location = \Biciclette\Location::get();
  // var_dump($location);
  $meteo = \Biciclette\Meteo::get($location['latlng']);
} catch (\Throwable $th) {
  var_dump($th->getMessage());
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />

  <!-- Meta Data -->
  <title>A Biciclette</title>
  <meta name="description" content="" />
  <!-- Critical style -->
  <style>
    * {
      box-sizing: border-box;
    }

    html,
    body {
      margin: 0;
      padding: 0;
      height: 100%;
      width: 100%;
    }
  </style>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Style -->
  <link rel="stylesheet" href="/static/css/style.css" />

  <meta name="viewport" content="initial-scale=1" />
</head>

<body>
  <header></header>

  <main>
    <div class="meteo--container">
      <?= $meteo['html'] ?>
    </div>
  </main>

  <footer></footer>

  <!-- JS -->
  <script src="/static/js/index.js" defer></script>
</body>

</html>