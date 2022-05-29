CREATE DATABASE homework;
USE homework;

CREATE TABLE users (
    id integer primary key auto_increment,
    username varchar(16) not null unique,
    atype varchar(16),
    password varchar(255) not null,
    email varchar(255) not null unique,
    propic varchar(255),
    since timestamp not null default current_timestamp
) Engine = InnoDB;

CREATE TABLE eventi ( 
    id integer primary key auto_increment,
    tipo varchar(16),
    descr varchar(255),
    data date,
    user varchar(16) not null,
    foreign key(user) references users(username) on delete cascade on update cascade
) Engine = InnoDB;

CREATE TABLE tracks (
    id integer primary key auto_increment,
    canzone varchar(255),
    img varchar(255),
    user integer not null,
    foreign key(user) references users(id) on delete cascade on update cascade
) Engine = InnoDB;

CREATE TABLE follows (
    userid integer not null,
    eventid integer not null,
    data timestamp not null default current_timestamp,
    index xuser(userid),
    index xevent(eventid),
    foreign key(userid) references users(id) on delete cascade on update cascade,
    foreign key(eventid) references eventi(id) on delete cascade on update cascade,
    primary key(userid, eventid)
) Engine = InnoDB;