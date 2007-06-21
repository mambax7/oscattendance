
CREATE TABLE `oscatt_events` (
  `event_id` mediumint(3) NOT NULL auto_increment,
  `event_Name` varchar(100) default NULL,
  `event_Place` varchar(100) default NULL,
  `datelastedited` datetime default NULL,
  `dateentered` datetime NOT NULL default '0000-00-00 00:00:00',
  `enteredby` smallint(5) unsigned NOT NULL default '0',
  `editedby` smallint(5) unsigned default '0',
  PRIMARY KEY  (`event_id`),
  UNIQUE KEY `event_Name` (`event_Name`)
);

CREATE TABLE `oscatt_attendance` (
  `att_id` mediumint(9) unsigned NOT NULL auto_increment,
  `event_id` mediumint(9) unsigned ,
  `att_Date` date NOT NULL default '0000-00-00',
  `datelastedited` datetime default NULL,
  `dateentered` datetime NOT NULL default '0000-00-00 00:00:00',
  `enteredby` smallint(5) unsigned NOT NULL default '0',
  `editedby` smallint(5) unsigned default '0',
  PRIMARY KEY  (`att_id`),
  UNIQUE KEY `att_unq`(`event_id`,`att_Date`)
) ;

CREATE TABLE `oscatt_attendance_person` (
  `att_id` mediumint(9) unsigned NOT NULL ,
  `att_personid` mediumint(9) unsigned ,
  PRIMARY KEY  (`att_id`,`att_personid` )
) ;

