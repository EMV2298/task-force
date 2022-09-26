<?php
$faker = Faker\Factory::create('ru_RU');
return [
  'name' => $faker->firstName(),
  'email' => $faker->email(),
  'dob' => $faker->date('Y-m-d', '2000-12-30'),
  'password' => '12345',
  'phonenumber' => $faker->phoneNumber(),
  'city_id' => random_int(1, 10),
  'description' => $faker->realText(),
  'status' => random_int(0,1),
  'show_contacts' => random_int(0,1),
  'avatar' => '/img/man-glasses.png',
];