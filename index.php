<?php

require_once "vendor/autoload.php";
use taskforce\business\Task;

$task1 = new Task(1, NULL, 'new');

var_dump($task1->getAvailableActions(1));
var_dump($task1->getNextStatus('cancel'));
var_dump(new Task(1, NULL, 'new'));