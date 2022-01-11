<?php

require_once __DIR__ . '/vendor/autoload.php';

// Getting info
$latlng = null;
$messages = [];
try {
  // $location = \Biciclette\Location::getJSON();
  $latlng = \Biciclette\Address::get('mairie Notre-Dame-des-Landes', 44130);
} catch (\Throwable $th) {
  $messages[] = genErrorMessage('AddressError', $th);
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
    <?php if (count($messages) > 0) : ?>
      <?php foreach ($messages as $message) : ?>
        <div class="message message--<?= $message['type'] ?>">
          <?= $message['message'] ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </header>

  <main>
    <div id="map"></div>
  </main>

  <footer></footer>

  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
  <!-- JS -->
  <script>
    const ndlLatLng = <?= json_encode($latlng ? $latlng['data'] : null) ?>;
  </script>
  <script src="./static/js/circulations.js" defer></script>
</body>

</html>