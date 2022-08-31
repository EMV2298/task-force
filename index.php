<?php

require_once "vendor/autoload.php";
use taskforce\business\Task;

$executor = NULL;

$task1 = new Task(1, $executor, 'new');

var_dump($task1->getAvailableActions(1));

