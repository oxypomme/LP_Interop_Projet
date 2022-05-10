<?php

require_once __DIR__ . '/vendor/autoload.php';

// Disable warnings on prod
if ($_SERVER['SERVER_NAME'] === 'webetu.iutnc.univ-lorraine.fr') {
  error_reporting(E_ERROR);
}

/**
 * Functon to uniform error messages
 *
 * @param string $name Service name who failed
 * @param Throwable $th Error
 *
 * @return array Type & message
 */
function genErrorMessage(string $name, Throwable &$th): array
{
  $msg = $name . ': ' . $th->getMessage();
  // Add more debug if not in prod
  if ($_SERVER['SERVER_NAME'] !== 'webetu.iutnc.univ-lorraine.fr') {
    $msg .= ' -> <b>' . $th->getFile() . ':' . $th->getLine() . '</b>';
  }
  return ['type' => 'error', 'message' => $msg];
}

/**
 * Check if `$_SERVER['REQUEST_URI']` match the given `$route`
 *
 * @param string $route Route to check
 *
 * @return bool `true` if match, `else` if not match
 */
$uriMatch = fn (string $route) => preg_match("/\/$route(\.php|\/)?$/", $_SERVER['REQUEST_URI']);

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
} else if ($uriMatch('') || $uriMatch('velo')) {
  exit(require './velo.php');
} else if ($uriMatch('circulations')) {
  exit(require './circulations.php');
}
