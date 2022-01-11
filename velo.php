<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/php/Location.php';

// Disable warning on prod
if ($_SERVER['SERVER_NAME'] === 'webetu.iutnc.univ-lorraine.fr') {
  error_reporting(E_ERROR);
}

// Serving static files
$staticPos = strpos($_SERVER['REQUEST_URI'], '/static');
if ($staticPos !== false && $staticPos <= 1) {
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

// Routing
if (preg_match('/\/circulations(\.php)?/', $_SERVER['REQUEST_URI'])) {
  return require './circulations.php';
}

// Getting info
$meteo = null;
$velos = null;
$messages = [];
try {
  $location = \Biciclette\Location::get();
  $meteo = \Biciclette\Meteo::get($location['latlng']);
  $velos = \Biciclette\Velo::get();
} catch (\Throwable $th) {
  $messages[] = ['type' => 'error', 'message' => $th->getMessage()];
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
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
  <!-- Style -->
  <link rel="stylesheet" href="./static/css/velos.css" />

  <meta name="viewport" content="initial-scale=1" />
</head>

<body>
  <header>
    <h1>♬ A bicyclette ♪</h1>
    <?php if (count($messages) > 0) : ?>
      <?php foreach ($messages as $message) : ?>
        <div class="message message--<?= $message['type'] ?>">
          <?= $message['message'] ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </header>

  <aside>
    <div class="meteo--container">
      <?= $meteo ? $meteo['html'] : '' ?>
    </div>
  </aside>

  <main>
    <div class="velos--container">
      <div id="map"></div>
    </div>
  </main>

  <footer>
    <p>SUBLET Tom - LP CIASIE 2021-2022 - LP2</p>
  </footer>

  <!-- JS -->
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
  <script>
    const geoloc = <?= json_encode($location ?? null) ?>;
    const velos = <?= json_encode($velos ? $velos['data'] : null) ?>;
  </script>
  <script src="./static/js/velos.js" defer></script>
</body>

</html>