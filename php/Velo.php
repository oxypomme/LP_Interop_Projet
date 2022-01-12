<?php

namespace Biciclette;

class Velo
{
  static function get(): array
  {
    $data = (new Request('http://www.velostanlib.fr/service/carto'))->fetchXML();

    $stations = [];

    /**
     * @var \SimpleXMLElement $marker
     */
    foreach ($data['payload']->xpath('markers/marker') as $marker) {
      $attrs = current($marker->attributes());
      if ($attrs['open'] == '1') {
        try {
          $detailsData = (new Request('http://www.velostanlib.fr/service/stationdetails/nancy/' . $attrs["number"], [], false, true))->fetchXML();

          $stations[] = [
            'id' => $attrs["number"],
            'name' => explode(' - ', $attrs['name'], 2)[1],
            'address' => $attrs['address'],
            'latlng' => [$attrs['lat'], $attrs['lng']],
            'available' => $detailsData['payload']->xpath('available')[0]->__toString(),
            'free' => $detailsData['payload']->xpath('free')[0]->__toString(),
          ];
        } catch (\Throwable $th) {
          continue;
        }
      }
    }

    return ['url' => $data['url'], 'data' => $stations];
  }
}
