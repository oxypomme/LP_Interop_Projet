<?php

namespace Biciclette;


class IP
{
  static function get(string $ip): array
  {
    $data = Request::fetch("http://ip-api.com/xml/$ip");
    if (str_contains($data['headers'][0], '200')) {
      $obj = simplexml_load_string($data['payload']);
      return [
        'country' => $obj->xpath('country')[0]->__toString(),
        'city' => $obj->xpath('city')[0]->__toString()
      ];
    } else {
      throw $http_response_header[0];
    }
  }
}

// var_dump($obj);
