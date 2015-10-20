-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: cch
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.14.04.1-log

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
-- Table structure for table `cch_district`
--

DROP TABLE IF EXISTS `cch_district`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cch_district` (
  `district_id` varchar(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `region` varchar(100) NOT NULL,
  `status` varchar(200) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `country` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cch_district`
--

LOCK TABLES `cch_district` WRITE;
/*!40000 ALTER TABLE `cch_district` DISABLE KEYS */;
INSERT INTO `cch_district` VALUES ('Accra Metro','Accra Metro','Greater Accra','active','2015-04-17 04:15:59',NULL,NULL,'2015-04-17 08:15:59','Ghana'),('Ada East','Ada East','Greater Accra','active','2015-04-17 04:15:59',NULL,NULL,'2015-04-17 08:15:59','Ghana'),('Ada West','Ada West','Greater Accra','active','2015-04-17 04:15:59',NULL,NULL,'2015-04-17 08:15:59','Ghana'),('Ho Municipal','Ho Municipal','Volta','active','2015-04-17 04:15:59',NULL,NULL,'2015-04-17 08:15:59','Ghana'),('La Dade Kotopon','La Dade Kotopon','Greater Accra','active','2015-04-17 04:15:59',NULL,NULL,'2015-04-17 08:15:59','Ghana'),('Ningo Prampram','Ningo Prampram','Greater Accra','active','2015-04-17 04:15:59',NULL,NULL,'2015-04-17 08:15:59','Ghana'),('South Dayi','South Dayi','Volta','active','2015-04-17 04:15:59',NULL,NULL,'2015-04-17 08:15:59','Ghana'),('South Tongu','South Tongu','Volta','active','2015-04-17 04:15:59',NULL,NULL,'2015-04-17 08:15:59','Ghana');
/*!40000 ALTER TABLE `cch_district` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-11  7:46:08
