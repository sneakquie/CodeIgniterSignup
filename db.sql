-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2014 at 01:27 AM
-- Server version: 5.5.34
-- PHP Version: 5.3.10-1ubuntu3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

CREATE TABLE IF NOT EXISTS `invites` (
  `user_id` int(11) NOT NULL,
  `invite` varchar(32) NOT NULL,
  PRIMARY KEY (`invite`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(31) NOT NULL,
  `email` varchar(127) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `salt` varchar(6) NOT NULL,
  `social_login` varchar(127) DEFAULT NULL,
  `group` int(3) NOT NULL,
  `language` varchar(3) DEFAULT NULL,
  `timezone` varchar(127) DEFAULT NULL,
  `signup_date` int(11) DEFAULT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `inviter` int(11) NOT NULL DEFAULT '0',
  `last_activity` int(11) DEFAULT NULL,
  `signup_ip` varchar(45) DEFAULT NULL,
  `last_ip` varchar(45) DEFAULT NULL,
  `last_change` int(11) NOT NULL DEFAULT '0',
  `recover_expire` int(11) NOT NULL,
  `recover_code` varchar(32) DEFAULT NULL,
  `confirm_code` varchar(32) DEFAULT NULL,
  `real_name` varchar(63) DEFAULT NULL,
  `born_date` int(11) DEFAULT NULL,
  `show_born_date` tinyint(1) NOT NULL DEFAULT '1',
  `show_email` tinyint(1) NOT NULL DEFAULT '1',
  `location` varchar(127) DEFAULT NULL,
  `about` varchar(160) DEFAULT NULL,
  `gender` smallint(1) NOT NULL DEFAULT '0',
  `website` varchar(255) DEFAULT NULL,
  `network_facebook` varchar(127) DEFAULT NULL,
  `network_vkontakte` varchar(127) DEFAULT NULL,
  `network_twitter` varchar(127) DEFAULT NULL,
  `network_steam` varchar(127) DEFAULT NULL,
  `network_flickr` varchar(127) DEFAULT NULL,
  `network_vimeo` varchar(127) DEFAULT NULL,
  `network_youtube` varchar(127) DEFAULT NULL,
  `network_googleplus` varchar(127) DEFAULT NULL,
  `network_odnoklassniki` varchar(127) DEFAULT NULL,
  `network_tumblr` varchar(127) DEFAULT NULL,
  `show_last_login` tinyint(1) NOT NULL DEFAULT '1',
  `notify_comments` tinyint(1) NOT NULL DEFAULT '1',
  `notify_comments_email` tinyint(1) NOT NULL DEFAULT '0',
  `notify_answers` tinyint(1) NOT NULL DEFAULT '1',
  `notify_answers_email` tinyint(1) NOT NULL DEFAULT '0',
  `notify_messages` tinyint(1) NOT NULL DEFAULT '1',
  `notify_messages_email` tinyint(1) NOT NULL DEFAULT '0',
  `notify_follow_news` tinyint(1) NOT NULL DEFAULT '1',
  `notify_cats_news` tinyint(1) NOT NULL DEFAULT '1',
  `notify_likes` tinyint(1) NOT NULL DEFAULT '1',
  `allow_email` tinyint(1) NOT NULL DEFAULT '1',
  `photo_avatar` varchar(127) DEFAULT NULL,
  `photo_cover` varchar(127) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `recover_code` (`recover_code`,`confirm_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;