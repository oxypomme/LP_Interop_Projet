<?php

namespace Biciclette;

class Request
{
  /**
   * @var array History of requested URLs
   */
  public static array $history = [];

  /**
   * @var string URL of the API
   */
  private string $url;
  /**
   * @var array GET parameters as a associative array
   */
  private array $query = [];
  /**
   * @var bool|string If we cache data, if string it means the duration of the cache
   */
  private bool|string $cache = false;
  /**
   * @var bool If we skip the history writing (usefull for sub requests)
   */
  private bool $skipHistory = false;

  /**
   * Prepare HTTP request
   * 
   * @param string $url URL of the API
   * @param array $query GET parameters as a associative array
   * @param bool|string $cache If we cache data, if `string` it means the duration of the cache
   * @param bool $skipHistory If we skip the history writing (usefull for sub requests)
   */
  function __construct(string $url, array $query = [], bool|string $cache = false, bool $skipHistory = false)
  {
    $this->url = $url;
    $this->query = $query;
    $this->cache = $cache;
    $this->skipHistory = $skipHistory;
  }

  /**
   * Fetch data using object vars
   * 
   * @return array|null Fetched data
   */
  function fetch(): ?array
  {
    // Parsing query
    $gparams = implode(
      '&',
      array_map(
        fn (string $key, ?string $value) =>
        $value && trim($value) ?
          "$key=" . htmlentities(preg_replace('/\s/', '+', $value)) :
          '',
        array_keys($this->query),
        $this->query
      )
    );
    $url = $this->url . (count($this->query) > 0 ? "?$gparams" : '');

    // Setting up proxy for production
    $context = null;
    if ($_SERVER['SERVER_NAME'] === 'webetu.iutnc.univ-lorraine.fr') {
      $opts = array('http' => array('proxy' => 'tcp://www-cache:3128', 'request_fulluri' => true));
      $context = stream_context_create($opts);
    }
    // Adding url to history
    if (!$this->skipHistory) {
      self::$history[] = $url;
    }

    // Cecking cache
    if ($this->cache) {
      $matches = [];
      preg_match('/.*\/(.*)\.?.*$/', $this->url, $matches);
      if (count($matches) <= 1) {
        throw new \Error('No suitable filename found for cache');
      }
      $filename = $matches[1];

      $path = __DIR__ . "/../cache/{$filename}.json";
      if (file_exists($path)) {
        $data = json_decode(file_get_contents($path), true);
        // If data is not too old (1 day)
        if (
          \DateTime::createFromFormat('c', $data['date_created'])
          < (new \DateTime())->add(\DateInterval::createFromDateString(gettype($this->cache) === 'boolean' ? '1 day' : $this->cache))
        ) {
          return $data;
        }
      }
    }

    // Fetching content
    $res = file_get_contents($url, false, $context);
    if ($res === false) {
      return null;
    }
    if (
      !str_contains($http_response_header[0], '200')
      && !str_contains($http_response_header[0], '302')
    ) {
      throw new \Error('Error occured on fetch (' . $url . ')');
    }
    $data = ['url' => $url, 'payload' => $res, 'headers' => $http_response_header];

    // Writing to cache
    if ($this->cache) {
      $data = array_merge(
        $data,
        [
          'date_created' => date('c')
        ]
      );
      file_put_contents($path, json_encode($data));
    }
    return $data;
  }

  /**
   * Fetch data and transform it as a associative array.
   * 
   * In this particuliar case, `payload` is a generator function who yields each 
   * line as a associative array.
   * First line of CSV is considered as column names (and so as keys).
   * Works this way becausee we need to treat big files (> 5Mo).
   * 
   * @param string $separator Separator between columns. `,` by default.
   * @param string $EOL Separator between lines. `\n` by default.
   * 
   * @return null|array Fetched data.
   */
  function fetchCSV(string $separator = ',', string $EOL = PHP_EOL): ?array
  {
    $data = $this->fetch();
    if ($data['payload']) {
      // Split betwen lines
      $lines = explode($EOL, $data['payload']);
      $labels = explode($separator, $lines[0]);
      $data['payload'] = function () use ($separator, &$lines, &$labels) {
        for ($j = 1; $j < count($lines); $j++) {
          $cells = explode($separator, $lines[$j]);

          $obj = [];
          for ($i = 0; $i < count($cells); $i++) {
            $obj[$labels[$i] ?? $i] = $cells[$i];
          }
          yield $obj;
        }
      };
    }
    return $data;
  }

  /**
   * Fetch data and transform it as a asssociative array (via `json_decode`)
   * 
   * @return array|null Fetched data
   */
  function fetchJSON(): ?array
  {
    $data = $this->fetch();
    if ($data['payload']) {
      $data['payload'] = json_decode($data['payload']);
    }
    return $data;
  }

  /**
   * Fetch data and transform it as a XMLDocument
   * 
   * @return array|null Fetched data
   */
  function fetchXML(): ?array
  {
    $data = $this->fetch();
    if ($data['payload']) {
      $data['payload'] = simplexml_load_string($data['payload']);
    }
    return $data;
  }
}
