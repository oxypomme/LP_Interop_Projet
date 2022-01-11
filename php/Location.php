<?php

namespace Biciclette;


class Location
{
  private static function createRequest(string $format): Request
  {
    //! WARN: Dirty, if we're behind a proxy but not in IUT it's will be wrong
    //! BUG: Not the correct IP
    $ip = in_array(explode('.', $_SERVER['REMOTE_ADDR'])[0], ['100', '172']) ? '194.214.170.34' : $_SERVER['REMOTE_ADDR'];
    return new Request("http://ip-api.com/$format/$ip", [
      'fields' => implode(',', [
        'status', 'country', 'city', 'lat', 'lon', 'zip'
      ])
    ]);
  }

  static function getXML(): array
  {
    $data = self::createRequest('xml')->fetchXML();
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

  static function getJSON(): array
  {
    $data = self::createRequest('json')->fetchJSON();
    if ($data['payload']['status'] != 'fail') {
      return [
        'url' => $data['url'],
        'country' => $data['payload']['country'],
        'city' => $data['payload']['city'],
        'zip' => $data['payload']['zip'],
        'latlng' => [$data['payload']['lat'], $data['payload']['lon']]
      ];
    } else {
      throw new \Error('Error occured on ip fetch (' . $data['url'] . ')');
    }
  }
}

// var_dump($obj);
