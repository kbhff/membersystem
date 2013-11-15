-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb5+lenny6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 11, 2013 at 09:43 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kbhff`
--

-- --------------------------------------------------------

--
-- Table structure for table `drenge`
--

CREATE TABLE IF NOT EXISTS `drenge` (
  `navn` varchar(150) character set utf8 collate utf8_danish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_chores`
--

CREATE TABLE IF NOT EXISTS `ff_chores` (
  `chore` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `date` date NOT NULL,
  `changed` date NOT NULL,
  `uid` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_chore_types`
--

CREATE TABLE IF NOT EXISTS `ff_chore_types` (
  `name` varchar(40) collate latin1_danish_ci NOT NULL,
  `auth` int(11) NOT NULL,
  `uid` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_divisions`
--

CREATE TABLE IF NOT EXISTS `ff_divisions` (
  `name` varchar(30) collate utf8_danish_ci NOT NULL,
  `type` varchar(10) collate utf8_danish_ci NOT NULL,
  `webmembers` varchar(1) collate utf8_danish_ci NOT NULL,
  `kontakt` varchar(150) collate utf8_danish_ci NOT NULL,
  `uid` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_division_chores`
--

CREATE TABLE IF NOT EXISTS `ff_division_chores` (
  `division` int(11) NOT NULL,
  `chore` int(11) NOT NULL,
  `needed` int(11) NOT NULL,
  `comment` varchar(100) collate latin1_danish_ci NOT NULL,
  `uid` int(11) NOT NULL auto_increment,
  `tid` varchar(11) collate latin1_danish_ci NOT NULL,
  PRIMARY KEY  (`uid`),
  KEY `division` (`division`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_division_members`
--

CREATE TABLE IF NOT EXISTS `ff_division_members` (
  `division` int(11) NOT NULL,
  `member` int(11) NOT NULL,
  `start` date NOT NULL,
  `exit` date NOT NULL,
  KEY `member` (`member`),
  KEY `division` (`division`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_division_newmemberinfo`
--

CREATE TABLE IF NOT EXISTS `ff_division_newmemberinfo` (
  `division` int(11) NOT NULL,
  `support` text collate latin1_danish_ci NOT NULL,
  `welcome` text collate latin1_danish_ci NOT NULL,
  PRIMARY KEY  (`division`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_groupmembers`
--

CREATE TABLE IF NOT EXISTS `ff_groupmembers` (
  `group` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `puid` int(11) NOT NULL,
  `status` varchar(20) collate latin1_danish_ci NOT NULL,
  `note` varchar(50) collate latin1_danish_ci NOT NULL,
  `valid_from` date NOT NULL,
  `expires` date NOT NULL,
  UNIQUE KEY `un` (`group`,`department`,`puid`),
  KEY `group` (`group`),
  KEY `department` (`department`),
  KEY `puid` (`puid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_groups`
--

CREATE TABLE IF NOT EXISTS `ff_groups` (
  `name` varchar(35) collate latin1_danish_ci NOT NULL,
  `type` varchar(20) collate latin1_danish_ci NOT NULL,
  `uid` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_itemdays`
--

CREATE TABLE IF NOT EXISTS `ff_itemdays` (
  `item` int(11) NOT NULL,
  `pickupday` int(11) NOT NULL,
  `lastorder` datetime NOT NULL,
  KEY `item` (`item`),
  KEY `day` (`pickupday`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_items`
--

CREATE TABLE IF NOT EXISTS `ff_items` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `division` int(11) NOT NULL,
  `units` double unsigned NOT NULL,
  `measure` enum('stk.','gram','kilo','bundt','pose','kasse','enhed') collate utf8_danish_ci NOT NULL,
  `producttype_id` mediumint(9) unsigned NOT NULL,
  `amount` double NOT NULL COMMENT 'amount in money',
  PRIMARY KEY  (`id`),
  KEY `quantity` (`measure`),
  KEY `producttype` (`producttype_id`),
  KEY `division` (`division`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_jobs`
--

CREATE TABLE IF NOT EXISTS `ff_jobs` (
  `division` int(11) NOT NULL,
  `job` varchar(20) collate latin1_danish_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `creator` int(11) NOT NULL,
  `uid` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_log`
--

CREATE TABLE IF NOT EXISTS `ff_log` (
  `creator` int(11) NOT NULL,
  `member` int(11) NOT NULL,
  `type` varchar(30) collate latin1_danish_ci NOT NULL,
  `text` varchar(200) collate latin1_danish_ci NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_mail_aliases`
--

CREATE TABLE IF NOT EXISTS `ff_mail_aliases` (
  `puid` int(10) NOT NULL,
  `master` varchar(200) collate latin1_danish_ci NOT NULL,
  `alias` varchar(200) collate latin1_danish_ci NOT NULL,
  PRIMARY KEY  (`puid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_massmail_log`
--

CREATE TABLE IF NOT EXISTS `ff_massmail_log` (
  `sent` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `subject` varchar(250) collate latin1_danish_ci NOT NULL,
  `content` text collate latin1_danish_ci NOT NULL,
  `sender` int(11) NOT NULL,
  `division` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `privacy` varchar(1) collate latin1_danish_ci NOT NULL,
  `num` int(11) NOT NULL,
  `uid` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci AUTO_INCREMENT=408 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_membernote`
--

CREATE TABLE IF NOT EXISTS `ff_membernote` (
  `puid` int(11) NOT NULL,
  `note` varchar(250) collate latin1_danish_ci NOT NULL,
  `editedby` int(11) NOT NULL,
  `changed` datetime NOT NULL,
  `created` datetime NOT NULL,
  KEY `puid` (`puid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_orderhead`
--

CREATE TABLE IF NOT EXISTS `ff_orderhead` (
  `puid` int(10) unsigned default NULL,
  `orderno` int(10) unsigned default NULL,
  `orderkey` varchar(150) collate utf8_danish_ci default NULL,
  `cc_trans_date` date default NULL,
  `cc_trans_amount` decimal(10,2) default NULL,
  `cc_trans_vat_amount` decimal(10,2) default NULL,
  `cc_trans_no` varchar(25) collate utf8_danish_ci default NULL,
  `status1` varchar(25) collate utf8_danish_ci default NULL,
  `status2` varchar(25) collate utf8_danish_ci default NULL,
  `status3` varchar(25) collate utf8_danish_ci default NULL,
  `created` timestamp NULL default CURRENT_TIMESTAMP,
  `changed` timestamp NULL default NULL,
  `uid` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`uid`),
  KEY `status1` (`status1`),
  KEY `personuid` (`puid`),
  KEY `sordidx` (`orderno`,`status1`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=24937 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_orderlines`
--

CREATE TABLE IF NOT EXISTS `ff_orderlines` (
  `orderno` int(10) unsigned default NULL,
  `orderkey` varchar(150) collate utf8_danish_ci default NULL,
  `item` varchar(20) collate utf8_danish_ci default NULL,
  `quant` int(11) default NULL,
  `iteminfo` int(11) default NULL,
  `puid` int(11) default NULL,
  `amount` decimal(10,2) default NULL,
  `vat_amount` decimal(10,2) default NULL,
  `status1` varchar(25) collate utf8_danish_ci NOT NULL,
  `status2` varchar(25) collate utf8_danish_ci NOT NULL,
  `status3` varchar(25) collate utf8_danish_ci NOT NULL,
  `created` timestamp NULL default CURRENT_TIMESTAMP,
  `changed` timestamp NULL default NULL,
  `uid` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`uid`),
  KEY `status1` (`status1`),
  KEY `puid` (`puid`),
  KEY `itemadd` (`quant`),
  KEY `item` (`item`),
  KEY `sordidx` (`orderno`,`item`),
  KEY `iteminfo` (`iteminfo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=32901 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_persons`
--

CREATE TABLE IF NOT EXISTS `ff_persons` (
  `firstname` varchar(50) collate utf8_danish_ci default NULL,
  `middlename` varchar(50) collate utf8_danish_ci NOT NULL,
  `lastname` varchar(50) collate utf8_danish_ci default NULL,
  `sex` char(1) collate utf8_danish_ci default NULL,
  `adr1` varchar(50) collate utf8_danish_ci default NULL,
  `adr2` varchar(50) collate utf8_danish_ci default NULL,
  `streetno` varchar(15) collate utf8_danish_ci default NULL,
  `floor` varchar(15) collate utf8_danish_ci default NULL,
  `door` varchar(5) collate utf8_danish_ci NOT NULL,
  `adr3` varchar(50) collate utf8_danish_ci default NULL,
  `zip` varchar(10) collate utf8_danish_ci default NULL,
  `city` varchar(40) collate utf8_danish_ci default NULL,
  `country` varchar(40) collate utf8_danish_ci default NULL,
  `languagepref` varchar(40) collate utf8_danish_ci default NULL,
  `tel` varchar(30) collate utf8_danish_ci default NULL,
  `tel2` varchar(30) collate utf8_danish_ci default NULL,
  `email` varchar(150) collate utf8_danish_ci default NULL,
  `birthday` date default NULL,
  `user_activation_key` varchar(12) collate utf8_danish_ci default NULL,
  `password` varchar(32) collate utf8_danish_ci default NULL,
  `status1` varchar(25) collate utf8_danish_ci default NULL,
  `status2` varchar(25) collate utf8_danish_ci default NULL,
  `status3` varchar(25) collate utf8_danish_ci default NULL,
  `rights` int(15) default NULL,
  `privacy` varchar(15) collate utf8_danish_ci NOT NULL,
  `active` enum('no','yes','X') collate utf8_danish_ci NOT NULL default 'yes',
  `ownupdate` date default NULL,
  `last_login` timestamp NOT NULL default '0000-00-00 00:00:00',
  `created` timestamp NULL default CURRENT_TIMESTAMP,
  `changed` date default NULL,
  `uid` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`uid`),
  KEY `firstname` (`firstname`),
  KEY `rights` (`rights`),
  KEY `tel` (`tel`),
  KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=8745 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_persons_info`
--

CREATE TABLE IF NOT EXISTS `ff_persons_info` (
  `puid` int(11) NOT NULL,
  `membersince` varchar(12) collate latin1_danish_ci NOT NULL,
  `kollektiv` varchar(1) collate latin1_danish_ci NOT NULL,
  `remark` varchar(200) collate latin1_danish_ci NOT NULL,
  PRIMARY KEY  (`puid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_pickupdates`
--

CREATE TABLE IF NOT EXISTS `ff_pickupdates` (
  `division` int(11) NOT NULL,
  `pickupdate` date NOT NULL,
  `uid` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=698 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_pickupdatessav`
--

CREATE TABLE IF NOT EXISTS `ff_pickupdatessav` (
  `division` int(11) NOT NULL,
  `pickupdate` date NOT NULL,
  `lastorder` datetime NOT NULL,
  `units` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `note` varchar(50) collate utf8_danish_ci NOT NULL,
  `uid` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=161 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_producttypes`
--

CREATE TABLE IF NOT EXISTS `ff_producttypes` (
  `id` mediumint(9) unsigned NOT NULL auto_increment,
  `explained` varchar(100) collate utf8_danish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_reportfields`
--

CREATE TABLE IF NOT EXISTS `ff_reportfields` (
  `type` varchar(30) character set utf8 collate utf8_danish_ci NOT NULL,
  `name` varchar(30) character set utf8 collate utf8_danish_ci NOT NULL,
  `editable` varchar(1) collate latin1_danish_ci NOT NULL default 'Y',
  `noterequired` varchar(1) collate latin1_danish_ci NOT NULL default 'N',
  `sort` int(11) NOT NULL,
  `comment` varchar(100) character set utf8 collate utf8_danish_ci NOT NULL,
  `uid` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_report_data`
--

CREATE TABLE IF NOT EXISTS `ff_report_data` (
  `field` int(11) NOT NULL,
  `data` decimal(10,2) NOT NULL,
  `creator` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `note` varchar(150) character set utf8 collate utf8_danish_ci NOT NULL,
  `changed` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`field`,`date`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_roles`
--

CREATE TABLE IF NOT EXISTS `ff_roles` (
  `role` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `puid` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `auth_by` int(11) NOT NULL,
  `status` varchar(20) collate latin1_danish_ci NOT NULL,
  `valid_from` datetime NOT NULL,
  `expires` datetime NOT NULL,
  UNIQUE KEY `un` (`role`,`department`,`puid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_sessions`
--

CREATE TABLE IF NOT EXISTS `ff_sessions` (
  `session_id` varchar(40) collate latin1_danish_ci NOT NULL default '0',
  `ip_address` varchar(16) collate latin1_danish_ci NOT NULL default '0',
  `user_agent` varchar(50) collate latin1_danish_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text collate latin1_danish_ci DEFAULT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_statistics_log`
--

CREATE TABLE IF NOT EXISTS `ff_statistics_log` (
  `ary` blob NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY  (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_teams`
--

CREATE TABLE IF NOT EXISTS `ff_teams` (
  `name` varchar(20) collate latin1_danish_ci NOT NULL,
  `color` varchar(5) collate latin1_danish_ci NOT NULL,
  `id` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_transactions`
--

CREATE TABLE IF NOT EXISTS `ff_transactions` (
  `puid` int(11) NOT NULL,
  `orderno` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `authorized_by` int(11) NOT NULL,
  `comment` varchar(150) collate latin1_danish_ci NOT NULL,
  `method` varchar(30) collate latin1_danish_ci NOT NULL,
  `trans_id` varchar(15) collate latin1_danish_ci NOT NULL,
  `item` int(11) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `uid` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`uid`),
  KEY `person` (`puid`),
  KEY `date` (`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci AUTO_INCREMENT=18156 ;

-- --------------------------------------------------------

--
-- Table structure for table `ff_xfer`
--

CREATE TABLE IF NOT EXISTS `ff_xfer` (
  `membersince` varchar(10) collate latin1_danish_ci NOT NULL,
  `email` varchar(150) collate latin1_danish_ci NOT NULL,
  `phone` varchar(20) collate latin1_danish_ci NOT NULL,
  `kollektiv` varchar(20) collate latin1_danish_ci NOT NULL,
  `bem` varchar(200) collate latin1_danish_ci NOT NULL,
  `d1` varchar(10) collate latin1_danish_ci NOT NULL,
  `d2` varchar(10) collate latin1_danish_ci NOT NULL,
  `d3` varchar(10) collate latin1_danish_ci NOT NULL,
  `d4` varchar(10) collate latin1_danish_ci NOT NULL,
  `d5` varchar(10) collate latin1_danish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ff_zipcodes`
--

CREATE TABLE IF NOT EXISTS `ff_zipcodes` (
  `zip` varchar(10) collate utf8_danish_ci NOT NULL,
  `city` varchar(50) collate utf8_danish_ci NOT NULL,
  `street` varchar(50) collate utf8_danish_ci NOT NULL,
  `firm` varchar(55) collate utf8_danish_ci NOT NULL,
  `region` varchar(10) collate utf8_danish_ci NOT NULL,
  `country` tinyint(4) NOT NULL default '0',
  KEY `zip` (`zip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fornavne`
--

CREATE TABLE IF NOT EXISTS `fornavne` (
  `navn` varchar(20) character set utf8 collate utf8_danish_ci NOT NULL,
  `sex` varchar(1) collate latin1_danish_ci NOT NULL,
  KEY `navn` (`navn`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `piger`
--

CREATE TABLE IF NOT EXISTS `piger` (
  `navn` varchar(150) collate latin1_danish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unisex`
--

CREATE TABLE IF NOT EXISTS `unisex` (
  `navn` varchar(150) collate latin1_danish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
