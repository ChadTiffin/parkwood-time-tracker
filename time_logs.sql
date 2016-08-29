SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `time_logs`;
CREATE TABLE `time_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clocked_in` datetime NOT NULL,
  `clocked_out` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `time_logs` (`id`, `clocked_in`, `clocked_out`) VALUES
(2,	'2016-08-23 08:25:20',	'2016-08-23 16:32:00'),
(8,	'2016-08-24 09:00:18',	'2016-08-24 15:10:11'),
(9,	'2016-08-25 08:59:02',	'2016-08-25 16:40:55'),
(10,	'2016-08-22 08:50:00',	'2016-08-22 16:12:49'),
(18,	'2016-08-26 08:29:00',	'2016-08-26 15:58:23'),
(21,	'2016-08-29 08:52:12',	NULL);