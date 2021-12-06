<?php

namespace Biciclette;

class Request
{
  static function fetch($url): ?array
  {
    // Setting up proxy for production
    $context = null;
    if ($_SERVER['SERVER_NAME'] === 'webetu.iutnc.univ-lorraine.fr') {
      $opts = array('http' => array('proxy' => 'tcp://127.0.0.1:8080', 'request_fulluri' => true));
      $context = stream_context_create($opts);
    }
    $res = file_get_contents($url, false, $context);
    if ($res === false) {
      return null;
    }
    return ['payload' => $res, 'headers' => $http_response_header];
  }
}
