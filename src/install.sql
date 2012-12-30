CREATE TABLE `cctickets` (
  `OXID` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `OXUSERID` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `SUBJECT` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `STATE` int(1) NOT NULL,
  `CREATED` datetime NOT NULL,
  `UPDATED` datetime NOT NULL,
  PRIMARY KEY (`OXID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `cctickettexts` (
  `OXID` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `TICKETID` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `TEXT` text COLLATE latin1_general_ci NOT NULL,
  `TIMESTAMP` datetime NOT NULL,
  `AUTHOR` enum('admin','user') COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`OXID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;