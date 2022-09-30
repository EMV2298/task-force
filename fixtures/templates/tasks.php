<?php


use taskforce\business\Task;

$faker = Faker\Factory::create('ru_RU');
$statuses = Task::getAllStatuses();
$status = array_rand($statuses);
$executor = $status === Task::STATUS_NEW || $status === Task::STATUS_CANCELED ? null : random_int(1,20);
$customer = random_int(1,20);
while ($customer === $executor) {
    $customer = random_int(1,20);
  }

return [
  'customer_id' => $customer,
  'executor_id' => $executor,
  'title' => $faker->catchPhrase(),
  'description' => $faker->realText(),
  'category_id' => random_int(1,8),
  'city_id' => random_int(0,10),
  'budget' => random_int(1000, 10000),
  'status' => $status,
];