drop database if exists tongjiCircle;

create database tongjiCircle;

use tongjiCircle;

create table userInformation(
    uid int primary key auto_increment,
    email varchar(200) not null unique,
    password varchar(100) not null,
    userName varchar(60) not null,
    trueName varchar(60) not null,
    major varchar(100) not null,
    studyNumber varchar(100) not null,
    studyPassword varchar(100) not null,
    headImage longblob,
    imageType varchar(20),
    userDate date not null
);
alter table userInformation auto_increment=0;

create table article(
    aid int primary key auto_increment,
    uid int not null,
    title varchar(100) not null,
    introduction varchar(200) not null,
    content text not null,
    articleImage longblob,
    imageType varchar(20),
    type varchar(20) not null,
    articleTime datetime not null,
    readNumber int not null,
    foreign key (uid) references userInformation(uid) on delete cascade
);
alter table article auto_increment=0;

create table agreeArticle(
    uid int not null,
    aid int not null,
    agreeTime datetime not null,
    primary key(uid,aid),
    foreign key(uid) references userInformation(uid) on delete cascade,
    foreign key(aid) references article(aid) on delete cascade
);

create table loveArticle(
    uid int not null,
    aid int not null,
    loveTime datetime not null,
    primary key(uid,aid),
    foreign key(uid) references userInformation(uid) on delete cascade,
    foreign key(aid) references article(aid) on delete cascade
);

create table commentArticle(
    uid int not null,
    aid int not null,
    cid int not null,
    cidTarget int not null,
    content text not null,
    commentTime datetime not null,
    primary key(aid,cid),
    foreign key(uid) references userInformation(uid) on delete cascade,
    foreign key(aid) references article(aid) on delete cascade
);

create table activity(
    yid int primary key auto_increment,
    uid int not null,
    title varchar(100) not null,
    content text not null,
    location varchar(100) not null,
    activityImage longblob,
    imageType varchar(20),
    maxNum int not null,
    type varchar(20) not null,
    activityTime datetime not null,
    startTime datetime not null,
    endTime datetime not null,
    readNumber int not null,
    foreign key (uid) references userInformation(uid) on delete cascade
);
alter table activity auto_increment=0;

create table agreeActivity(
    uid int not null,
    yid int not null,
    agreeTime datetime not null,
    primary key(uid,yid),
    foreign key(uid) references userInformation(uid) on delete cascade,
    foreign key(yid) references activity(yid) on delete cascade
);

create table loveActivity(
    uid int not null,
    yid int not null,
    loveTime datetime not null,
    primary key(uid,yid),
    foreign key(uid) references userInformation(uid) on delete cascade,
    foreign key(yid) references activity(yid) on delete cascade
);

create table commentActivity(
    uid int not null,
    yid int not null,
    cid int not null,
    cidTarget int not null,
    content text not null,
    commentTime datetime not null,
    primary key(yid,cid),
    foreign key(uid) references userInformation(uid) on delete cascade,
    foreign key(yid) references activity(yid) on delete cascade
);

create table manager(
    mid int primary key auto_increment,
    uid int not null,
    foreign key(uid) references userInformation(uid) on delete cascade
);
alter table manager auto_increment=0;

create table announcement(
    nid int primary key auto_increment,
    mid int not null,
    title varchar(100) not null,
    content text not null,
    announcementTime datetime not null,
    readNumber int not null,
    foreign key(mid) references manager(mid) on delete cascade
);
alter table announcement auto_increment=0;

create table loveAnnouncement(
    uid int not null,
    nid int not null,
    loveTime datetime not null,
    primary key(uid,nid),
    foreign key(uid) references userInformation(uid) on delete cascade,
    foreign key(nid) references announcement(nid) on delete cascade
);

create table water(
    wid int primary key auto_increment,
    mid int not null,
    nid int not null,
    aid int not null,
    yid int not null,
    acid int not null,
    ycid int not null,
    type int not null,
    information varchar(300) not null,
    reason varchar(300) not null,
    waterTime datetime not null
);
alter table water auto_increment=0;
