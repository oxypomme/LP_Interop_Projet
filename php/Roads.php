<?php

namespace Biciclette;

class Roads
{
  static function get(): array
  {
    $data = (new Request('https://data.loire-atlantique.fr/api/records/1.0/search/', [
      'dataset' => '224400028_info-route-departementale'
    ]))->fetchJSON();

    $infos = [];
    foreach ($data['payload']->records as $info) {
      $infos[] = [
        'type' => $info->fields->type,
        'location' => $info->fields->ligne2,
        'nature' => $info->fields->nature,
        'duration' => $info->fields->ligne4,
        'latlng' => [
          $info->fields->latitude,
          $info->fields->longitude
        ]
      ];
    }
    return ['url' => $data['url'], 'data' => $infos];
  }
}
