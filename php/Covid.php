<?php

namespace Biciclette;

class Covid
{
  /**
   * Get COVID stats, filter it by departement and prepare it for graphs.
   *
   * Due to data size, this request is cached with default expiration (1 day).
   *
   * @param string $dep_code The departement of the data
   * 
   * @return array Data for graphs + last update
   * 
   * @see https://www.data.gouv.fr/fr/datasets/synthese-des-indicateurs-de-suivi-de-lepidemie-covid-19/
   */
  static function get(string $dep_code): array
  {
    $dep = substr($dep_code, 0, 2);
    $data = (new Request('https://www.data.gouv.fr/fr/datasets/r/5c4e1452-3850-4b59-b11c-3dd51d7fb8b5', [], true))->fetchCSV();

    $res = [];
    foreach ($data['payload']() as $row) {
      if (
        $row['dep'] == $dep
      ) {
        $res[] = [
          'date' => $row['date'],
          'hosp' => $row['hosp'],
          'rea' => $row['rea'],
          'tx_incid' => $row['tx_incid'],
          'incid_dchosp' => $row['incid_dchosp']
        ];
      }
    }

    return ['url' => $data['url'], 'data' => $res, 'last_update' => $data['date_created']];
  }
}
