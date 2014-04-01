DROP database if EXISTS tia;
CREATE DATABASE tia;
USE tia;

CREATE TABLE `tia`.`board` (
  `user` VARCHAR(45) NOT NULL,
  `proposal` VARCHAR(45) NULL,
  PRIMARY KEY (`user`));

insert into board values('tai@tai.it',NULL);
insert into board values('osor@osor.it',NULL);
insert into board values('tia@tia.it',NULL);
