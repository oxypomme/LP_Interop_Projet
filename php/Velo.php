<?php

namespace Biciclette;

class Velo
{
  static function get(): array
  {
    $data = (new Request('http://www.velostanlib.fr/service/carto'))->fetchXML();
    // Check if request is 200 OK
    if (
      str_contains($data['headers'][0], '200')
    ) {
      $stations = [];

      /**
       * @var \SimpleXMLElement $marker
       */
      foreach ($data['payload']->xpath('markers/marker') as $marker) {
        $attrs = current($marker->attributes());
        if ($attrs['open'] == '1') {
          $detailsData = (new Request('http://www.velostanlib.fr/service/stationdetails/nancy/' . $attrs["number"]))->fetchXML();
          if (
            str_contains($detailsData['headers'][0], '200')
          ) {
            $stations[] = [
              'id' => $attrs["number"],
              'name' => explode(' - ', $attrs['name'], 2)[1],
              'address' => $attrs['address'],
              'latlng' => [$attrs['lat'], $attrs['lng']],
              'available' => $detailsData['payload']->xpath('available')[0]->__toString(),
              'free' => $detailsData['payload']->xpath('free')[0]->__toString(),
            ];
          }
        }
      }

      return ['url' => $data['url'], 'data' => $stations];
    } else {
      throw new \Error('Error occured on velos fetch (' . $data['url'] . ')');
    }
  }
}

// var_dump($obj);
