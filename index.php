<?php

require_once "vendor/autoload.php";
use taskforce\business\Task;

$executor = NULL;
$task1 = new Task(12, $executor ?? 0, 'new');

var_dump($task1->getAvailableActions());
