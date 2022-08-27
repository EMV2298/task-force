<?php

require_once "vendor/autoload.php";
use taskforce\business\Task;

$task1 = new Task(12, 1, 'new');

var_dump($task1->getAvailableActions());
