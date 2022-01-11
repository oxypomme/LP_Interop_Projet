<?php

namespace Biciclette;

class Address
{
  static function get(string $search, ?int $postcode = null, ?string $type = null): array
  {
    $data = (new Request('https://api-adresse.data.gouv.fr/search/', [
      'q' => $search,
      'postcode' => $postcode,
      'type' => $type,
      'limit' => 1
    ]))->fetchJSON();

    if (count($data['payload']->features) > 0) {
      return [
        'url' => $data['url'], 'data' =>
        $data['payload']->features[0]->geometry->coordinates
      ];
    } else {
      throw new \Error('No address found (' . $data['url'] . ')');
    }
  }
}
