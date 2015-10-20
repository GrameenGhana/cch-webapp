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
-- Table structure for table `cch_sub_districts`
--

DROP TABLE IF EXISTS `cch_sub_districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cch_sub_districts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `district_id` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cch_sub_districts`
--

LOCK TABLES `cch_sub_districts` WRITE;
/*!40000 ALTER TABLE `cch_sub_districts` DISABLE KEYS */;
INSERT INTO `cch_sub_districts` VALUES (1,'Kasseh ','2','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(2,'Ada Foah','2','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(3,'Pediatorkope','2','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(4,'Bornikope Sub-Dist','3','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(5,'Sege Sub-District','3','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(6,'Anyamam Sub-Dist','3','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(8,'NYIGBENYA','6','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(9,'Prampram','6','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(10,'Great Ningo','6','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(11,'DAWHENYA','6','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(12,'AFIENYA ','6','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(13,'Kpeve-Adzokoe','7','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(14,'Peki','7','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(15,'Dzake','7','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(16,'Kpalime-Tongor','7','2015-06-25 16:04:15','2015-06-25 16:04:15','1'),(47,'Sub Ashanti OwnDevice','14','0000-00-00 00:00:00','2015-06-26 02:53:15','1'),(48,'Sub Brong OwnDevice','17','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(49,'Sub Central OwnDevice','16','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(50,'Sub East OwnDevice','13','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(51,'Sub GAR OwnDevice','11','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(52,'Sub Northern OwnDevice','18','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(53,'Sub Upper East OwnDevice','20','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(54,'Sub Upper West OwnDevice','19','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(55,'Sub Volta OwnDevice','12','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(56,'Sub Western OwnDevice','15','0000-00-00 00:00:00','2015-06-26 02:51:03','1'),(57,'Sotewu','8','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(58,'Dabala - Adutor','8','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(59,'Agorta - Gamenu','8','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(60,'Sogakope','8','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(61,'Dorkploame','8','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(62,'Dordoekope','8','0000-00-00 00:00:00','0000-00-00 00:00:00','1'),(63,'Test','19','2015-06-26 14:04:12','2015-06-26 14:04:12','1'),(64,'Ashiedu keteke','1','2015-06-29 20:46:36','2015-06-29 20:46:36','1'),(65,'Osu Kottey','1','2015-06-29 20:47:51','2015-06-29 20:47:51','1');
/*!40000 ALTER TABLE `cch_sub_districts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-11  7:34:35
