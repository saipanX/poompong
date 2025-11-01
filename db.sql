CREATE DATABASE IF NOT EXISTS coffee_shop;
USE coffee_shop;

CREATE TABLE coffees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  category VARCHAR(50),
  image VARCHAR(255) DEFAULT ''
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','staff') DEFAULT 'staff'
);

INSERT INTO users (username,password,role) VALUES
('admin', MD5('1234'),'admin'),
('staff', MD5('1234'),'staff');
