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

$content = "На ул.Кедышко с 08-39  в направлении д/с Калиновского  из-за ДТП стороннего транспорта  произошла остановка движения троллейбусных маршрутов №  35,37,38. Для троллейбусного  маршрута № 37  в направлении д/с Калиновского организован объезд по ул.Кнорина, Калиновского , маршруты № 35,38 следуют  до  ул.Толбухина. 	Движение восстановлено в 09-17";

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
