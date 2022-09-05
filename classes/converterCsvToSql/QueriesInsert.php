<?php

namespace taskforce\converterCsvToSql;

use SplFileObject;
use Exception;

class QueriesInsert
{

  private string $cvsPathFile;
  private string $sqlFileName;
  private string $tableName;

  public function __construct(string $cvsPathFile, string $sqlFileName, string $tableName)
  {
    if (!file_exists($cvsPathFile)) {
      throw new Exception('Файл не найден');
    }
    $this->cvsPathFile = $cvsPathFile;
    $this->sqlFileName = $sqlFileName;
    $this->tableName = $tableName;
  }

  private function getSqlTypeString($array)
  {
    $elemets = [];
    foreach ($array as $el) {
      array_push($elemets, "'{$el}'");
    }
    return implode(", ", $elemets);
  }

  public function convertToSql()
  {
    $data = new SplFileObject($this->cvsPathFile);
    $data->setFlags(SplFileObject::READ_CSV);
    $header = implode(", ", $data->current());

    //Убирает неизвестный символ в начале
    $header = trim($header, "\xEF\xBB\xBF");

    $sqlStrings = [];

    foreach ($data as $values) {
      array_push($sqlStrings, "({$this->getSqlTypeString($values)}),\r\n");
    }
    array_shift($sqlStrings);
    array_unshift($sqlStrings, "INSERT INTO $this->tableName ($header) VALUES \r\n");
    $sqlStrings[array_key_last($sqlStrings)] = substr($sqlStrings[array_key_last($sqlStrings)], 0, -3);

    return file_put_contents("data\\" . $this->sqlFileName . ".sql", $sqlStrings);
  }
}
