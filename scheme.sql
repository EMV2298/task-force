CREATE DATABASE taskforce
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  category VARCHAR(128) NOT NULL,
  name VARCHAR(128) NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  name VARCHAR(64) NOT NULL,
  email VARCHAR(128) UNIQUE NOT NULL,
  dob DATE NOT NULL,
  password VARCHAR(128) NOT NULL,
  phonenumber VARCHAR(128) NOT NULL,
  telegram VARCHAR(128) NULL,
  city VARCHAR(128) NULL,
  avatar TINYTEXT NULL,
  description TEXT NULL,
  status BOOL NOT NULL,
  show_contacts BOOL NOT NULL
);

CREATE TABLE executor_categories (
  user_id INT NOT NULL,
  category_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (category_id) REFERENCES categories (id),
  PRIMARY KEY (user_id, category_id)
);

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  customer_id INT NOT NULL,
  executor_id INT NULL,
  title TINYTEXT NOT NULL,
  description TEXT NULL,
  category_id INT NOT NULL,
  address TINYTEXT NULL,
  budget MEDIUMINT NULL,
  date_completion DATE NULL,  
  status TINYTEXT NOT NULL,
  FOREIGN KEY (customer_id) REFERENCES users (id),
  FOREIGN KEY (executor_id) REFERENCES users (id),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);
CREATE FULLTEXT INDEX search ON tasks(title);

CREATE TABLE files (
  task_id INT NOT NULL,
  file_name TINYTEXT NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE offers (
  task_id INT NOT NULL,
  executor_id INT NOT NULL,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  message TEXT NOT NULL,
  price INT NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks (id),
  FOREIGN KEY (executor_id) REFERENCES users (id),
  PRIMARY KEY (task_id, executor_id)
);

CREATE TABLE reviews (
  customer_id INT NOT NULL,
  executor_id INT NOT NULL,
  task_id INT NOT NULL,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  message TEXT,
  rating INT NOT NULL,
  FOREIGN KEY (customer_id) REFERENCES users (id),
  FOREIGN KEY (executor_id) REFERENCES users (id),
  FOREIGN KEY (task_id) REFERENCES tasks (id),
  PRIMARY KEY (customer_id, executor_id, task_id)
);


