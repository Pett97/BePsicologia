


drop table if exists cars;

create table cars (
	id int auto_increment primary key,
    name varchar(55) not null
);

drop table if exists brands;

create table brands(
	id int auto_increment primary key,
    name varchar(55) not null
);

drop table if exists users;

create table users(
	id int auto_increment primary key,
    name varchar(255) not null,
    email varchar(50) unique not null,
    password varchar(250) not null
)
