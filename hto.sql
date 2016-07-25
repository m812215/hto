-- MySQL dump 10.13  Distrib 5.1.61, for apple-darwin10.3.0 (i386)
--
-- Host: localhost    Database: hto
-- ------------------------------------------------------
-- Server version	5.1.61

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
-- Table structure for table `date`
--

DROP TABLE IF EXISTS `date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `date` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `canceled` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `date_person`
--

DROP TABLE IF EXISTS `date_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `date_person` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `facebook_id` int(10) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `from_time` time DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `f_ctq_pilot` int(1) DEFAULT NULL,
  `f_xdz_pilot` int(1) DEFAULT NULL,
  `f_ctq_only` int(1) DEFAULT NULL,
  `f_xdz_only` int(1) DEFAULT NULL,
  `f_licenced` int(1) DEFAULT NULL,
  `f_unhappy` int(1) DEFAULT NULL,
  `f_i_aff` int(1) DEFAULT NULL,
  `f_i_sl` int(1) DEFAULT NULL,
  `f_i_radio` int(1) DEFAULT NULL,
  `f_i_fs` int(1) DEFAULT NULL,
  `f_i_tandem` int(1) DEFAULT NULL,
  `f_s_aff` int(1) DEFAULT NULL,
  `f_s_aff2` int(1) DEFAULT NULL,
  `f_s_sl` int(1) DEFAULT NULL,
  `f_s_radio` int(1) DEFAULT NULL,
  `f_s_fs` int(1) DEFAULT NULL,
  `f_s_tandem` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2056 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-06-25 22:50:34
