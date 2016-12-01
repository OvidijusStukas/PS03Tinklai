CREATE TABLE IF NOT EXISTS `user_roles`
(
  `userRoleId` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,

  PRIMARY KEY (`userRoleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_roles` (name)
VALUES ('ROLE_ADMIN'), ('ROLE_ACCOUNTANT'), ('ROLE_USER');

CREATE TABLE IF NOT EXISTS `plan_plans`
(
  `planId` INT(11) NOT NULL AUTO_INCREMENT,
  `name` NVARCHAR(60) NOT NULL,
  `description` TEXT NOT NULL,
  `fixedPrice` DECIMAL(19, 2) NOT NULL,
  `fixedCount` DECIMAL(19, 2) NOT NULL,

  PRIMARY KEY (`planId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `plan_restrictions`
(
  `planRestrictionId` INT(11) NOT NULL AUTO_INCREMENT,
  `planId` INT(11) NOT NULL,
  `price` DECIMAL(19, 2) NOT NULL,
  `mbCount` DECIMAL(19, 2) NOT NULL,

  PRIMARY KEY (`planRestrictionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_users`
(
  `userId` INT(11) NOT NULL AUTO_INCREMENT,
  `userRoleId` INT(11) NOT NULL,
  `username` NVARCHAR(50) NOT NULL,
  `password` NVARCHAR(60) NOT NULL,
  `email` NVARCHAR(254) NOT NULL,
  `planId` INT(11) NULL,

  PRIMARY KEY (`userId`),
  FOREIGN KEY (`userRoleId`) REFERENCES `user_roles` (`userRoleId`),
  FOREIGN KEY (`planId`) REFERENCES `plan_plans` (`planId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `accounting_period`
(
  `periodId` INT(11) NOT NULL AUTO_INCREMENT,
  `periodFrom` DATETIME NOT NULL,
  `periodTo` DATETIME NOT NULL,
  `isActive` BIT,

  PRIMARY KEY (`periodId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `accounting_records`
(
  `recordId` INT(11) NOT NULL  AUTO_INCREMENT,
  `periodId` INT(11) NOT NULL,
  `userId` INT(11) NOT NULL,
  `mbUsed` DECIMAL(19, 2) NOT NULL,
  `amountPaid` DECIMAL(19, 2) NOT NULL,
  `amountDebt` DECIMAL(19, 2) NOT NULL,

  PRIMARY KEY (`recordId`),
  FOREIGN KEY (`periodId`) REFERENCES `accounting_period` (`periodId`),
  FOREIGN KEY (`userId`) REFERENCES `user_users` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `message_messages`
(
  `messageId` INT NOT NULL AUTO_INCREMENT,
  `text` TEXT NOT NULL,
  `reciepentUserId` INT(11) NOT NULL,
  `senderUserId` INT(11) NOT NULL,

  PRIMARY KEY (`messageId`),
  FOREIGN KEY (`reciepentUserId`) REFERENCES `user_users` (`userId`),
  FOREIGN KEY (`senderUserId`) REFERENCES `user_users` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;