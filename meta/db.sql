-- MySQL dump 10.13  Distrib 5.5.30, for Linux (x86_64)
--
-- Host: localhost    Database: chess
-- ------------------------------------------------------
-- Server version	5.5.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `codes`
--

DROP TABLE IF EXISTS `codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codes` (
  `type` char(3) DEFAULT NULL,
  `code` char(3) DEFAULT NULL,
  `parent` char(3) DEFAULT NULL,
  `long_code` char(64) DEFAULT NULL,
  `description` char(64) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `codetypes`
--

DROP TABLE IF EXISTS `codetypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codetypes` (
  `code` char(3) DEFAULT NULL,
  `long_code` char(64) DEFAULT NULL,
  `description` char(64) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `long_code` (`long_code`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `user` char(32) DEFAULT NULL,
  `body` text,
  `subject_line` char(32) DEFAULT NULL,
  `type` char(3) DEFAULT NULL,
  `subject` bigint(20) unsigned DEFAULT NULL,
  `mtime_posted` bigint(20) unsigned DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `owner` char(32) DEFAULT NULL,
  `white` char(32) DEFAULT NULL,
  `black` char(32) DEFAULT NULL,
  `state` char(3) DEFAULT NULL,
  `fen` char(88) DEFAULT NULL,
  `mtime_start` bigint(20) unsigned DEFAULT NULL,
  `mtime_finish` bigint(20) unsigned DEFAULT NULL,
  `last_move_index` int(10) unsigned DEFAULT NULL,
  `tables` bigint(20) unsigned DEFAULT NULL,
  `type` char(3) DEFAULT NULL,
  `variant` char(3) DEFAULT NULL,
  `subvariant` char(3) DEFAULT NULL,
  `bughouse_other_game` char(75) DEFAULT NULL,
  `format` char(3) DEFAULT NULL,
  `result` char(1) DEFAULT NULL,
  `result_details` char(3) DEFAULT NULL,
  `white_rating_old` int(10) unsigned DEFAULT NULL,
  `white_rating_new` int(10) unsigned DEFAULT NULL,
  `black_rating_old` int(10) unsigned DEFAULT NULL,
  `black_rating_new` int(10) unsigned DEFAULT NULL,
  `clock_start_index` int(11) DEFAULT NULL,
  `clock_start_delay` int(10) unsigned DEFAULT NULL,
  `timing_initial` bigint(20) unsigned DEFAULT NULL,
  `timing_increment` bigint(20) unsigned DEFAULT NULL,
  `timing_style` char(3) DEFAULT NULL,
  `timing_overtime` bit(1) DEFAULT NULL,
  `timing_overtime_cutoff` smallint(5) unsigned DEFAULT NULL,
  `timing_overtime_increment` bigint(20) unsigned DEFAULT NULL,
  `event_type` char(3) DEFAULT NULL,
  `event` bigint(20) unsigned DEFAULT NULL,
  `round` int(10) unsigned DEFAULT NULL,
  `threefold_claimable` bit(1) DEFAULT NULL,
  `fiftymove_claimable` bit(1) DEFAULT NULL,
  `draw_offered` bit(1) DEFAULT NULL,
  `undo_requested` bit(1) DEFAULT NULL,
  `undo_granted` bit(1) DEFAULT NULL,
  `rated` bit(1) DEFAULT NULL,
  `mtime_last_update` bigint(20) unsigned DEFAULT NULL,
  `gid` char(75) DEFAULT NULL,
  `game_id` int(10) unsigned NOT NULL DEFAULT '0',
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1369 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `generic_updates`
--

DROP TABLE IF EXISTS `generic_updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `generic_updates` (
  `user` char(32) DEFAULT NULL,
  `type` char(3) DEFAULT NULL,
  `last_updated` bigint(20) unsigned DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i1` (`user`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=759 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invites`
--

DROP TABLE IF EXISTS `invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invites` (
  `tables` bigint(20) unsigned DEFAULT NULL,
  `user` char(32) DEFAULT NULL,
  `mtime_created` bigint(20) unsigned DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `longpolls`
--

DROP TABLE IF EXISTS `longpolls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `longpolls` (
  `user` char(32) DEFAULT NULL,
  `window_id` char(16) DEFAULT NULL,
  `session_id` bigint(20) unsigned DEFAULT NULL,
  UNIQUE KEY `tab` (`user`,`window_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `sender` char(32) DEFAULT NULL,
  `recipient` char(32) DEFAULT NULL,
  `type` char(3) DEFAULT NULL,
  `subject` bigint(20) unsigned DEFAULT NULL,
  `mtime_sent` bigint(20) unsigned DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `moves`
--

DROP TABLE IF EXISTS `moves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moves` (
  `move_index` int(10) unsigned DEFAULT NULL,
  `label_piece` char(1) DEFAULT NULL,
  `label_disambiguation` char(2) DEFAULT NULL,
  `label_sign` char(1) DEFAULT NULL,
  `label_to` char(2) DEFAULT NULL,
  `label_special` char(5) DEFAULT NULL,
  `label_check` char(1) DEFAULT NULL,
  `label_notes` char(2) DEFAULT NULL,
  `colour` tinyint(3) unsigned DEFAULT NULL,
  `fs` tinyint(3) unsigned DEFAULT NULL,
  `ts` tinyint(3) unsigned DEFAULT NULL,
  `mtime` bigint(20) unsigned DEFAULT NULL,
  `fen` char(88) DEFAULT NULL,
  `capture` smallint(5) unsigned DEFAULT NULL,
  `promote_to` smallint(5) unsigned DEFAULT NULL,
  `premove` bit(1) DEFAULT NULL,
  `gid` char(32) DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7600 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pieces_taken`
--

DROP TABLE IF EXISTS `pieces_taken`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pieces_taken` (
  `colour` tinyint(3) unsigned DEFAULT NULL,
  `type` tinyint(3) unsigned DEFAULT NULL,
  `piece` tinyint(3) unsigned DEFAULT NULL,
  `gid` char(32) DEFAULT NULL,
  `mtime` bigint(20) unsigned DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=625 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `premoves`
--

DROP TABLE IF EXISTS `premoves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `premoves` (
  `user` char(32) DEFAULT NULL,
  `gid` char(75) DEFAULT NULL,
  `fs` smallint(5) unsigned DEFAULT NULL,
  `ts` smallint(5) unsigned DEFAULT NULL,
  `promote_to` smallint(5) unsigned DEFAULT NULL,
  `move_index` smallint(5) unsigned DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ratings` (
  `user` char(32) DEFAULT NULL,
  `type` char(3) DEFAULT NULL,
  `variant` char(3) DEFAULT NULL,
  `format` char(3) DEFAULT NULL,
  `rating` double DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relationships`
--

DROP TABLE IF EXISTS `relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relationships` (
  `usera` char(32) DEFAULT NULL,
  `userb` char(32) DEFAULT NULL,
  `type` char(3) DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `seats`
--

DROP TABLE IF EXISTS `seats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seats` (
  `user` char(32) DEFAULT NULL,
  `tables` bigint(20) unsigned DEFAULT NULL,
  `gid` char(32) DEFAULT NULL,
  `game_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` char(3) DEFAULT NULL,
  `colour` tinyint(3) unsigned DEFAULT NULL,
  `ready` bit(1) DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1477 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tables` (
  `owner` char(32) DEFAULT NULL,
  `owner_rating` int(10) unsigned DEFAULT NULL,
  `accept_rating_min` int(10) unsigned DEFAULT NULL,
  `accept_rating_max` int(10) unsigned DEFAULT NULL,
  `choose_colour` bit(1) DEFAULT NULL,
  `challenge_colour` tinyint(3) unsigned DEFAULT NULL,
  `challenge_accepted` bit(1) DEFAULT NULL,
  `challenge_type` char(3) DEFAULT NULL,
  `owner_rematch_ready` bit(1) DEFAULT b'0',
  `guest_rematch_ready` bit(1) DEFAULT b'0',
  `type` char(3) DEFAULT NULL,
  `variant` char(3) DEFAULT NULL,
  `subvariant` char(3) DEFAULT NULL,
  `last_starting_fen` char(88) DEFAULT NULL,
  `score_owner` double DEFAULT NULL,
  `score_guest` double DEFAULT NULL,
  `event_type` char(3) DEFAULT NULL,
  `event` bigint(20) unsigned DEFAULT NULL,
  `fen` char(88) DEFAULT NULL,
  `timing_initial` bigint(20) unsigned DEFAULT NULL,
  `timing_increment` bigint(20) unsigned DEFAULT NULL,
  `timing_style` char(3) DEFAULT NULL,
  `timing_overtime` bit(1) DEFAULT NULL,
  `timing_overtime_cutoff` smallint(5) unsigned DEFAULT NULL,
  `timing_overtime_increment` bigint(20) unsigned DEFAULT NULL,
  `format` char(3) DEFAULT NULL,
  `alternate_colours` bit(1) DEFAULT NULL,
  `chess960_randomise_mode` char(3) DEFAULT NULL,
  `permissions_watch` char(3) DEFAULT NULL,
  `permissions_play` char(3) DEFAULT NULL,
  `rated` bit(1) DEFAULT NULL,
  `game_in_progress` bit(1) DEFAULT NULL,
  `games_played` bigint(20) unsigned NOT NULL DEFAULT '0',
  `mtime_last_update` bigint(20) unsigned DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1290 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `a` int(11) DEFAULT NULL,
  `b` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_prefs`
--

DROP TABLE IF EXISTS `user_prefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_prefs` (
  `user` char(32) DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `board_style` char(3) DEFAULT NULL,
  `piece_style` char(3) DEFAULT NULL,
  `board_size` int(11) DEFAULT NULL,
  `show_coords` bit(1) DEFAULT NULL,
  `sound` bit(1) DEFAULT NULL,
  `highlight_last_move` bit(1) DEFAULT NULL,
  `highlight_possible_moves` bit(1) DEFAULT NULL,
  `animate_moves` bit(1) DEFAULT NULL,
  `board_colour_light` char(6) DEFAULT NULL,
  `board_colour_dark` char(6) DEFAULT NULL,
  `premove` bit(1) DEFAULT NULL,
  `auto_promote` bit(1) DEFAULT NULL,
  `auto_promote_to` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `username` char(32) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `email` char(128) DEFAULT NULL,
  `join_date` bigint(20) unsigned DEFAULT NULL,
  `quick_challenges_as_white` bigint(20) unsigned NOT NULL DEFAULT '0',
  `quick_challenges_as_black` bigint(20) unsigned NOT NULL DEFAULT '0',
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-05-23 23:52:32
