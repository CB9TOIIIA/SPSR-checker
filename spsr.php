<?php
require_once './vendor/autoload.php'; // composer autoload.php

// Get needed classes
use JBZoo\HttpClient\HttpClient;

// Configure client (no options required!)
$httpClient = new HttpClient([
    'auth'            => [          // Simple HTTP auth
        'http-user-name',
        'http-password'
    ],
    'headers'         => [          // You custom headers
        'Cookie' => 'PHPSESSID=iu98onnu8d8hcgumqvkqbo0go6; _ym_uid=146270914526501172; BITRIX_SM_SALE_UID=580011485; _ga=GA1.2.519029704.1462709145; session_check=1462709377; rrlpuid=; rrpvid=414469202665878; CITY=chelni; svyaznoy_partner_uid=; svyaznoy_partner_tduid=; svyaznoy_partner_campaign=; tgCas=%28not%20set%29; rrlevt=1469013347066; AUTORIZ=0; SV_REFERER_LIST=%5B%22email_may16_19_subscr_mmr_2902081%2030.5%22%2C%22email_may16_24_meizu_m3_preorder_2904950%2030.5%22%2C%22type_in%2030.5%22%2C%22type_in%206.6%22%2C%22vk%2014.6%22%2C%22type_in%2019.6%22%2C%22promo%2021.6%22%2C%22promo%2021.6%22%2C%22promo%2030.6%22%2C%22promo%2030.6%22%2C%22promo%2020.7%22%2C%22yandex.ru%2020.7%22%2C%22yandex%2020.7%22%5D; __SourceTracker=yandex__cpc; __cpatrack=yandex__cpc; tgClc=yandex; PHPSESSID=033775edca1adba311ed866d03e29a4d; SHOWCITYMESSAGE=1; userFilterType=new; _comagic_idQSKXY=444665973.709709126.1470042711; _comagic_banner_stateQSKXY=%5Bobject%20Object%5D',
    ],
    'driver'          => 'auto',    // (Auto|Guzzle5|Guzzle6|Rmccue)
    'timeout'         => 10,        // Wait in seconds
    'verify'          => false,     // Check cert for SSL
    'exceptions'      => false,     // Show exceptions for statuses 4xx and 5xx
    'allow_redirects' => true,      // Show real 3xx-header or result?
    'max_redirects'   => 10,        // How much to reirect?
    'user_agent'      => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.34 Safari/537.36', // Custom UA
]);

$trackcode = 'AECA0000000000RU1';

// Just request
$response = $httpClient->request('http://www.spsr.ru/sites/default/modules/spsr/publicapi/monitoring.php?number='.$trackcode.'&lang=ru','post');
//
// $body = $response->body;
// dump($body,0,'$body');
$json = $response->parseXml();
$events = $json->find('event');
$lastres = $json->find('event.value');
$datenow = date('d.m.Y', strtotime($lastres['Date']));
foreach ($events->value as $event) {

  $date = date('d.m.Y', strtotime($event['Date']));
  $EventName = $event['EventName'];
  $City = $event['City'];
  if ($date == date('d.m.Y')) {
    file_get_contents('https://pushall.ru/api.php?type=self&id=0000&key=70000000000000000000000000000000&text='.$date.PHP_EOL.$EventName.PHP_EOL.$City.PHP_EOL.'');

  }
}
// echo $datenow.' '.$lastres['EventName'];
// dump($lastres,0,'$lastres');
?>
