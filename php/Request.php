<?php

namespace Biciclette;

class Request
{
  private string $url;
  private array $query = [];

  function __construct(string $url, array $query = [])
  {
    $this->url = $url;
    $this->query = $query;
  }

  function fetch(): ?array
  {
    // Parsing query
    $gparams = implode('&', array_map(fn (string $key, string $value) => "$key=$value", array_keys($this->query), $this->query));
    $url = $this->url . (count($this->query) > 0 ? "?$gparams" : '');
    // Setting up proxy for production
    $context = null;
    if ($_SERVER['SERVER_NAME'] === 'webetu.iutnc.univ-lorraine.fr') {
      $opts = array('http' => array('proxy' => 'tcp://www-cache:3128', 'request_fulluri' => true));
      $context = stream_context_create($opts);
    }
    // Fetching content
    $res = file_get_contents($url, false, $context);
    if ($res === false) {
      return null;
    }
    if (!str_contains($http_response_header[0], '200')) {
      throw new \Error('Error occured on fetch (' . $url . ')');
    }
    return ['url' => $url, 'payload' => $res, 'headers' => $http_response_header];
  }

  function fetchJSON(): ?array
  {
    $data = $this->fetch();
    if ($data['payload']) {
      $data['payload'] = json_decode($data['payload']);
    }
    return $data;
  }

  function fetchXML(): ?array
  {
    $data = $this->fetch();
    if ($data['payload']) {
      $data['payload'] = simplexml_load_string($data['payload']);
    }
    return $data;
  }
}
