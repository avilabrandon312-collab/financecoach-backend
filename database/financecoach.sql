CREATE DATABASE IF NOT EXISTS financecoach;

USE financecoach;

CREATE TABLE users (

id INT AUTO_INCREMENT PRIMARY KEY,

name VARCHAR(100) NOT NULL,

email VARCHAR(150) UNIQUE NOT NULL,

password VARCHAR(255) NOT NULL,

streak INT DEFAULT 0,

created_at TIMESTAMP
DEFAULT CURRENT_TIMESTAMP

);

CREATE TABLE accounts (

id INT AUTO_INCREMENT PRIMARY KEY,

user_id INT NOT NULL,

name VARCHAR(100),

type ENUM(
'personal',
'business'
),

balance DECIMAL(
12,
2
)
DEFAULT 0,

FOREIGN KEY (
user_id
)
REFERENCES users(id)

);

CREATE TABLE transactions (

id INT AUTO_INCREMENT PRIMARY KEY,

account_id INT NOT NULL,

type ENUM(
'income',
'expense',
'transfer'
),

category VARCHAR(100),

amount DECIMAL(
12,
2
),

description TEXT,

created_at TIMESTAMP
DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (
account_id
)
REFERENCES accounts(id)

);

CREATE TABLE goals (

id INT AUTO_INCREMENT PRIMARY KEY,

user_id INT,

name VARCHAR(150),

target_amount DECIMAL(
12,
2
),

saved_amount DECIMAL(
12,
2
)
DEFAULT 0,

deadline DATE,

FOREIGN KEY (
user_id
)
REFERENCES users(id)

);
