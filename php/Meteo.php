<?php

namespace Biciclette;

class Meteo
{
  static function get(array $latlng): array
  {
    $data = (new Request('https://www.infoclimat.fr/public-api/gfs/xml', [
      '_ll' => implode(',', $latlng),
      '_auth' => 'ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D',
      '_c' => '19f3aa7d766b6ba91191c8be71dd1ab2'
    ]))->fetch();
    // Check if request is 200 OK
    if (
      str_contains($data['headers'][0], '200')
    ) {
      $xmldoc = new \DOMDocument();
      $xmldoc->loadXML($data['payload']);
      // Check if response is 200 OK
      foreach ($xmldoc->getElementsByTagName('request_state') as $status) {
        if ($status->nodeValue != 200) {
          throw new \Error('Error occured on infoclimat (' . $data['url'] . ')');
        }
      }

      $xsldoc = new \DOMDocument();
      $xsldoc->load(__DIR__ . '/xsl/meteo.xsl');

      $xsl = new \XSLTProcessor();
      $xsl->setParameter('', 'date', date('Y-m-d'));
      $xsl->importStyleSheet($xsldoc);
      $frag = $xsl->transformToXML($xmldoc);

      return ['url' => $data['url'], 'html' => $frag];
    } else {
      throw new \Error('Error occured on meteo fetch (' . $data['url'] . ')');
    }
  }
}

// var_dump($obj);
