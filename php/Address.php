<?php

namespace Biciclette;

class Address
{
  /**
   * Search for address position (latitude & longitude) and parse
   * result
   * 
   * @param string $search The address to search
   * @param int|null $postcode The zip code of the address. Can refine results.
   * @param string|null $type The type of address. See doc for more info. Can refine results.
   * 
   * @return array Coordinates in `latlng` format
   * 
   * @see https://adresse.data.gouv.fr/api-doc/adresse
   */
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
        'url' => $data['url'],
        'data' => array_reverse($data['payload']->features[0]->geometry->coordinates)
      ];
    } else {
      throw new \Error('No address found (' . $data['url'] . ')');
    }
  }
}
