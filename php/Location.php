<?php

namespace Biciclette;


class Location
{
  static function get(): array
  {
    //! WARN: Dirty, if we're behind a proxy but not in IUT it's will be wrong
    $ip = in_array(explode('.', $_SERVER['REMOTE_ADDR'])[0], ['100', '172']) ? '194.214.170.34' : $_SERVER['REMOTE_ADDR'];
    $data = (new Request("http://ip-api.com/xml/$ip", [
      'fields' => implode(',', [
        'status', 'country', 'city', 'lat', 'lon', 'zip'
      ]),
    ]))->fetchXML();
    if (
      $data['payload']->xpath('status')[0]->__toString() != 'fail'
    ) {
      return [
        'url' => $data['url'],
        'country' => $data['payload']->xpath('country')[0]->__toString(),
        'city' => $data['payload']->xpath('city')[0]->__toString(),
        'zip' => $data['payload']->xpath('zip')[0]->__toString(),
        'latlng' => [$data['payload']->xpath('lat')[0]->__toString(), $data['payload']->xpath('lon')[0]->__toString()]
      ];
    } else {
      throw new \Error('Error occured on ip fetch (' . $data['url'] . ')');
    }
  }
}

// var_dump($obj);
