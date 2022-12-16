-- *********************************************
-- * SQL MySQL generation                      
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.2              
-- * Generator date: Sep 14 2021              
-- * Generation date: Mon Dec 12 20:30:01 2022 
-- * LUN file:  
-- * Schema: db_framework 
-- ********************************************* 


-- Database Section
-- ________________ 

create database db_framework;
use db_framework;


-- Tables Section
-- _____________ 

create table t_user (
     idUser int not null auto_increment,
     nickname varchar(255) not null,
     password varchar(255) not null,
     constraint ID_t_user_ID primary key (idUser));


-- Constraints Section
-- ___________________ 


-- Index Section
-- _____________ 

create unique index ID_t_user_IND
     on t_user (idUser);

