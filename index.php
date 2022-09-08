<?php

require_once "vendor/autoload.php";
use taskforce\business\Task;
use taskforce\converterCsvToSql\QueriesInsert;

$task1 = new Task(1, NULL, 'new');



QueriesInsert::convertToSql("data\cities.csv", "data\cities.sql");
QueriesInsert::convertToSql("data\categories.csv", "data\categories.sql");



var_dump($task1->getAvailableActions(1));
var_dump($task1->getNextStatus('cancel'));
var_dump(new Task(1, NULL, 'new'));
