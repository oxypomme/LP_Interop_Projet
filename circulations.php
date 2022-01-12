<?php

// In case of this script is called standalone
require_once __DIR__ . '/index.php';

// Getting info
$location = null;
$latlng = null;
$infos = null;
$covid = null;
$messages = [];
try {
  $latlng = \Biciclette\Address::get('mairie Notre-Dame-des-Landes', 44130);
} catch (\Throwable $th) {
  $messages[] = genErrorMessage('AddressError', $th);
}
try {
  $infos = \Biciclette\Roads::get();
} catch (\Throwable $th) {
  $messages[] = genErrorMessage('RoadError', $th);
}
try {
  $location = \Biciclette\Location::getJSON();
} catch (\Throwable $th) {
  $messages[] = genErrorMessage('LocationError', $th);
}
if ($location) {
  try {
    $covid = \Biciclette\Covid::get($location['zip']);
  } catch (\Throwable $th) {
    $messages[] = genErrorMessage('CovidError', $th);
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />

  <!-- Meta Data -->
  <title>Circulations</title>
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
  <link rel="stylesheet" href="./static/css/index.css" />
  <link rel="stylesheet" href="./static/css/circulations.css" />

  <meta name="viewport" content="initial-scale=1" />
</head>

<body>
  <header>
    <h1>Circulations</h1>
    <?php foreach ($messages as $message) : ?>
      <div class="message message--<?= $message['type'] ?>">
        <?= $message['message'] ?>
      </div>
    <?php endforeach; ?>
  </header>

  <aside>
    <div class="covid--container">
      <canvas width="400" height="400" id="graph-tx_incid"></canvas>
      <canvas width="400" height="400" id="graph-hosp"></canvas>
    </div>
  </aside>

  <main>
    <div id="map"></div>
  </main>

  <footer>
    <p>SUBLET Tom - LP CIASIE 2021-2022 - LP2</p>
    <ul class="fetch-history">
      <?php foreach (\Biciclette\Request::$history as $url) : ?>
        <li>
          <a href="<?= $url ?>">
            <?= preg_replace('/(https?:\/\/.*?\/).*/i', '$1', $url) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js" integrity="sha256-Y26AMvaIfrZ1EQU49pf6H4QzVTrOI8m9wQYKkftBt4s=" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
  <!-- JS -->
  <script>
    const ndlLatLng = <?= json_encode($latlng ? $latlng['data'] : null) ?>;
    const infos = <?= json_encode($infos ? $infos['data'] : null) ?>;
    const covid = <?= json_encode($covid ? $covid['data'] : null) ?>;
  </script>
  <script src="./static/js/circulations.js" defer></script>
</body>

</html>