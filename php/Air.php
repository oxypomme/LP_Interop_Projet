<?php

namespace Biciclette;

class Air
{
  /**
   * Get air quality of today in `$zone`
   *
   * @param string $zone The geographic zone concerned
   * 
   * @return array Hint about the air quality, label of the air quality and color of the label
   *
   * @see https://data-atmograndest.opendata.arcgis.com/datasets/atmograndest::ind-grandest/about
   */
  static function get(string $zone): array
  {
    $data = (new Request('https://services3.arcgis.com/Is0UwT37raQYl9Jj/arcgis/rest/services/ind_grandest/FeatureServer/0/query', [
      'where' => "lib_zone%3D%27$zone%27",
      'geometryType' => 'esriGeometryEnvelope',
      'spatialRel' => 'esriSpatialRelIntersects',
      'units' => 'esriSRUnit_Meter',
      'outFields' => '*',
      'returnGeometry' => 'true',
      'featureEncoding' => 'esriDefault',
      'multipatchOption' => 'xyFootprint',
      'returnExceededLimitFeatures' => 'true',
      'resultRecordCount' => 1,
      'f' => 'json'
    ]))->fetchJSON();

    $hint = '';
    switch ($data['payload']->features[0]->attributes->code_qual) {
      case '1':
        // Bon
        $hint = 'Prend un bol, grand si possible, l\'air est frais';
        break;
      case '2':
        // Moyen
        $hint = 'Ouvre les fenêtres';
        break;
      case '3':
        // Dégradé
        $hint = 'Ici c\'est Paris !';
        break;
      case '4':
        // Mauvais
        $hint = 'Sort le masque à gaz';
        break;
      case '5':
        // Très mauvais
        $hint = 'RIP tes poumons';
        break;
      case '6':
        // Extrèmement mauvais
        $hint = 'Comment t\'est en vie ?';
        break;
      default:
        break;
    }


    return ['url' => $data['url'], 'data' => [
      'hint' => $hint,
      'label' => $data['payload']->features[0]->attributes->lib_qual,
      'color' => $data['payload']->features[0]->attributes->coul_qual
    ]];
  }
}
