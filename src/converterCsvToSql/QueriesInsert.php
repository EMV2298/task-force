<?php

namespace taskforce\converterCsvToSql;

use SplFileObject;
use Exception;

class QueriesInsert
{
  private static function getSqlTypeString(array $array): string
  {
    $elemets = [];
    foreach ($array as $el) {
      $elemets[] = "\"{$el}\"";
    }
    return implode(", ", $elemets);
  }

  public static function convertToSql(string $cvsPathFile, string $sqlPathFile)
  {
    if (!file_exists($cvsPathFile)) {
      throw new Exception('Файл не найден');
    }

    $data = new SplFileObject($cvsPathFile);
    $data->setFlags(SplFileObject::READ_CSV);

    $tableName = $data->getFilename();
    $tableName = stristr($tableName, ".", true);
    
    $header = implode(", ", $data->current());        
    //Убирает неизвестный символ в начале
    $header = trim($header, "\xEF\xBB\xBF");

    $sqlStrings = [];

    foreach ($data as $values) {
      $values = self::getSqlTypeString($values);
      array_push($sqlStrings, "({$values}),\r\n");
    }
    array_shift($sqlStrings);
    array_unshift($sqlStrings, "INSERT INTO $tableName ($header) VALUES \r\n");
    $sqlStrings[array_key_last($sqlStrings)] = substr($sqlStrings[array_key_last($sqlStrings)], 0, -3);

    return file_put_contents($sqlPathFile, $sqlStrings);
  }
}
