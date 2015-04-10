<?php

function startsWithNumber($str){
  return (preg_match('/^\d/', $str) === 1);
}

function filterTransportNumber($number){

  if(!startsWithNumber($number)){
    return null;
  }

  $specialChars = [",", "&nbsp;", "\r", "\n"];
  $replaceChars = ["", "", "", ""];

  $number = str_replace($specialChars, $replaceChars, $number);
  $number = trim($number);
  return $number;

}

$content = "В связи с планируемым увеличением пассажиропотока на весенне&ndash;летний период: &nbsp; &nbsp;&nbsp; ОРГАНИЗОВЫВАЮТСЯрейсы на&nbsp; пригородных маршрутах: &nbsp;С 11 апреля 2015: &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; № 348С&laquo;ДС Карастояновой &ndash; с/т Боровцы&raquo; - (6.45, 8.40, 10.40, 15.35, 20.10) &ndash; сб., (9.30, 15.30, 17.30, 19.30) &ndash; вс. &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; № 226С&laquo;ДС&nbsp; Запад -3&ndash; Воловщина&raquo; - (10.17, 14.45)- сб., вс. &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; № 305С&laquo;ДС&nbsp; Запад -3&ndash; Крылово&raquo; - (7.45, 17.07)-сб., вс. &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; № 260С&laquo;АС Автозаводская &ndash; с/т Веселка&raquo; - (7.25, 8.55, 16.20)- сб., (10.20, 17.30, 19.05) &ndash; вс &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; №354С&laquo;ДС Славинского &ndash; с/т Оптика&raquo;&nbsp;&nbsp; - (8.50, 15.10, 17.00, 18.50)- вс. &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; № 364С&laquo;ДС Славинского &ndash; Психоневрологический интернат&raquo; - 16.30 &ndash; сб., вс. &nbsp;С 14 апреля 2015: &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; № 243C&nbsp;&laquo;ДС Карастояновой &ndash; с/т Трактор&raquo; - (7.06, 8.25, 18.20)- будн., 15.45-еж. ПРОДЛЕВАЮТСЯ: С 11 апреля 2015: &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; № 325&laquo;АС Юго-Западная &ndash; Богушово&raquo; до Дубенцов (№ 357)-6.00-сб., вс &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; №324&laquo;АС Юго-Западная &ndash; Богушово&raquo; до Дубенцов (№297)-20.00- сб., вс. С 12 апреля 2015: &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; № 202&laquo;ДС Карастояновой &ndash; Нелидовичи&raquo; до с/т Вишневка (№ 346) &ndash;&nbsp; 17.05 &ndash; вс. С 13апреля 2015: &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; № 277 &laquo;АС Юго-Западная &ndash; Городище&raquo; до Дубенцов (№357)- 12.20-будн. &middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; № 325&laquo;АС Юго-Западная &ndash; Богушово&raquo; до Дубенцов (№357)-20.30- будн. &nbsp;&nbsp;&nbsp;&nbsp; Одновременно отменяется выполнение рейса по м-ту № 364С&nbsp; - 12.35 &ndash; по субботам.";
$content = html_entity_decode($content);
echo $content.PHP_EOL;
die;

$specialChars = ["&nbsp;", "\r", "\n"];
$replaceChars = [" ", "", ""];

$filteredContent = str_replace($specialChars, $replaceChars, strip_tags($content));
echo($filteredContent).PHP_EOL;
$contentParts = explode(' ', $filteredContent);
$transports = [
  'bus' => 'автобус',
  'trolleybus' => 'троллейбус',
  'tram' => 'трамва',
];
foreach($transports as $transportName => $transportKeyWord){

  $parseNumbers = null;
  $skipChars = ['#', '№'];
  $numbers = [];

  $invalidCharsCounter = 0;

  foreach($contentParts as $contentPart){

    if(in_array($contentPart, $skipChars)){
      continue;
    }

    if($parseNumbers === null && (strpos($contentPart, $transportKeyWord) !== false)){
      $parseNumbers = true;
    }

    if($parseNumbers){
      if($invalidCharsCounter > 1){
        break;
      }

      if(strpos($contentPart, ',') !== false) {
        foreach ( explode( ',', $contentPart ) as $number ) {
          if ( $number = filterTransportNumber( $number ) ) {
            $numbers[] = $number;
          }
        }
      } elseif(startsWithNumber($contentPart)){
        if ( $number = filterTransportNumber( $contentPart ) ) {
          $numbers[] = $number;
        }
      } else {
        if($numbers){
          $invalidCharsCounter++;
        }
      }

    }
  }

  $numbers = array_unique($numbers);

  var_dump($transportName);
  var_dump($numbers);

}
