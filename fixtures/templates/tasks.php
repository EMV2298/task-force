<?php


use taskforce\business\Task;

$faker = Faker\Factory::create('ru_RU');
$statuses = Task::getAllStatuses();
return [
  'customer_id' => random_int(1,10),
  'executor_id' => random_int(1,10),
  'title' => $faker->catchPhrase(),
  'description' => $faker->realText(),
  'category_id' => random_int(1,8),
  'city_id' => random_int(1,10),
  'budget' => random_int(1000, 10000),
  'status' => array_rand($statuses, 1),




];