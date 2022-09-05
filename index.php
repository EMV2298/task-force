<?php

require_once "vendor/autoload.php";
use taskforce\business\Task;
use taskforce\converterCsvToSql\QueriesInsert;

$task1 = new Task(1, NULL, 'new');

$table1 = new QueriesInsert("data\cities.csv", "cities1", "cities");
$table2 = new QueriesInsert("data\categories.csv", "categories1", "categories");
$table1->convertToSql();
$table2->convertToSql();



var_dump($task1->getAvailableActions(1));
var_dump($task1->getNextStatus('cancel'));
var_dump(new Task(1, NULL, 'new'));
