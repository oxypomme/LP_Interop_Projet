<?php

require_once __DIR__ . '/vendor/autoload.php';

// Disable warning on prod
if ($_SERVER['SERVER_NAME'] === 'webetu.iutnc.univ-lorraine.fr') {
  error_reporting(E_ERROR);
}

function genErrorMessage(string $name, Throwable &$th): array
{
  $msg = $name . ': ' . $th->getMessage();
  // Add more debug if not in prod
  if ($_SERVER['SERVER_NAME'] !== 'webetu.iutnc.univ-lorraine.fr') {
    $msg .= ' -> <b>' . $th->getFile() . ':' . $th->getLine() . '</b>';
  }
  return ['type' => 'error', 'message' => $msg];
}

$uriMatch = fn (string $route) => preg_match("/\/$route(\.php|\/)?/", $_SERVER['REQUEST_URI']);

// Routing
if (preg_match("/\/static\/.+/", $_SERVER['REQUEST_URI'])) {
  // Serve static files
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
} else if ($_SERVER['REQUEST_URI'] == '/' || $uriMatch('velo')) {
  exit(require './velo.php');
} else if ($uriMatch('circulations')) {
  exit(require './circulations.php');
}
