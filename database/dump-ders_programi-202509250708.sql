-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ders_programi
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `google_id` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `picture` varchar(500) DEFAULT NULL,
  `role` enum('admin','editor','viewer') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `google_id` (`google_id`),
  KEY `idx_google_id` (`google_id`),
  KEY `idx_email` (`email`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'102421621348140497375','ufuk.tanyeri@gmail.com','Ufuk Tanyeri','https://lh3.googleusercontent.com/a/ACg8ocIXNIGOYN0p-GD6qDwlyWT3S7m-wj0kSlKWLY9k499Pr1cA6Qk=s96-c','admin','active','2025-09-25 03:58:47',NULL);
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `akademik_donemler`
--

DROP TABLE IF EXISTS `akademik_donemler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `akademik_donemler` (
  `donem_id` int(11) NOT NULL AUTO_INCREMENT,
  `donem_adi` varchar(50) NOT NULL,
  `baslangic_tarihi` date NOT NULL,
  `bitis_tarihi` date NOT NULL,
  `aktif` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`donem_id`),
  UNIQUE KEY `unique_donem` (`donem_adi`),
  KEY `idx_aktif_donem` (`aktif`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `akademik_donemler`
--

LOCK TABLES `akademik_donemler` WRITE;
/*!40000 ALTER TABLE `akademik_donemler` DISABLE KEYS */;
INSERT INTO `akademik_donemler` VALUES (1,'2024-2025 Güz','2024-09-15','2025-01-31',1,'2025-09-25 02:42:07');
/*!40000 ALTER TABLE `akademik_donemler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cakisma_loglari`
--

DROP TABLE IF EXISTS `cakisma_loglari`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cakisma_loglari` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `tur` enum('Öğretmen','Derslik','Program') NOT NULL,
  `kaynak_id` int(11) NOT NULL,
  `gun` tinyint(4) NOT NULL,
  `saat` time NOT NULL,
  `detay` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detay`)),
  `cozuldu` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`),
  KEY `idx_tur_kaynak` (`tur`,`kaynak_id`),
  KEY `idx_cozuldu` (`cozuldu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cakisma_loglari`
--

LOCK TABLES `cakisma_loglari` WRITE;
/*!40000 ALTER TABLE `cakisma_loglari` DISABLE KEYS */;
/*!40000 ALTER TABLE `cakisma_loglari` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ders_atamalari`
--

DROP TABLE IF EXISTS `ders_atamalari`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ders_atamalari` (
  `atama_id` int(11) NOT NULL AUTO_INCREMENT,
  `ders_id` int(11) NOT NULL,
  `ogretmen_id` int(11) NOT NULL,
  `donem_id` int(11) NOT NULL,
  `grup_no` int(11) DEFAULT 1,
  `kontenjan` int(11) DEFAULT 30,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`atama_id`),
  UNIQUE KEY `unique_atama` (`ders_id`,`ogretmen_id`,`donem_id`,`grup_no`),
  KEY `idx_ogretmen` (`ogretmen_id`),
  KEY `idx_donem` (`donem_id`),
  KEY `idx_da_composite` (`donem_id`,`ogretmen_id`,`ders_id`),
  CONSTRAINT `ders_atamalari_ibfk_1` FOREIGN KEY (`ders_id`) REFERENCES `dersler` (`ders_id`) ON DELETE CASCADE,
  CONSTRAINT `ders_atamalari_ibfk_2` FOREIGN KEY (`ogretmen_id`) REFERENCES `ogretim_elemanlari` (`ogretmen_id`) ON DELETE CASCADE,
  CONSTRAINT `ders_atamalari_ibfk_3` FOREIGN KEY (`donem_id`) REFERENCES `akademik_donemler` (`donem_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ders_atamalari`
--

LOCK TABLES `ders_atamalari` WRITE;
/*!40000 ALTER TABLE `ders_atamalari` DISABLE KEYS */;
INSERT INTO `ders_atamalari` VALUES (1,1,1,1,1,30,'2025-09-25 02:42:08','2025-09-25 02:42:08'),(2,5,5,1,1,30,'2025-09-25 02:42:08','2025-09-25 02:42:08'),(3,6,8,1,1,30,'2025-09-25 02:42:08','2025-09-25 02:42:08'),(4,7,12,1,1,30,'2025-09-25 02:42:08','2025-09-25 02:42:08'),(5,8,11,1,1,30,'2025-09-25 02:42:08','2025-09-25 02:42:08');
/*!40000 ALTER TABLE `ders_atamalari` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dersler`
--

DROP TABLE IF EXISTS `dersler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dersler` (
  `ders_id` int(11) NOT NULL AUTO_INCREMENT,
  `ders_kodu` varchar(20) DEFAULT NULL,
  `ders_adi` varchar(100) NOT NULL,
  `program_id` int(11) NOT NULL,
  `sinif` tinyint(4) NOT NULL CHECK (`sinif` in (1,2)),
  `donem` enum('Güz','Bahar','Yaz') DEFAULT 'Güz',
  `haftalik_saat` int(11) NOT NULL DEFAULT 2,
  `teorik_saat` int(11) DEFAULT 2,
  `uygulama_saat` int(11) DEFAULT 0,
  `lab_saat` int(11) DEFAULT 0,
  `kredi` int(11) DEFAULT 2,
  `akts` int(11) DEFAULT 3,
  `zorunlu` tinyint(1) DEFAULT 1,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ders_id`),
  UNIQUE KEY `ders_kodu` (`ders_kodu`),
  KEY `idx_program_sinif` (`program_id`,`sinif`),
  KEY `idx_ders_kodu` (`ders_kodu`),
  KEY `idx_ders_program` (`program_id`,`sinif`,`donem`),
  CONSTRAINT `dersler_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programlar` (`program_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dersler`
--

LOCK TABLES `dersler` WRITE;
/*!40000 ALTER TABLE `dersler` DISABLE KEYS */;
INSERT INTO `dersler` VALUES (1,'BP101','Atatürk İlkeleri ve İnkılap Tarihi I',1,1,'Güz',2,2,0,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(2,'BP102','Bilgisayar Donanımı ve Ağ',1,1,'Güz',3,2,1,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(3,'BP103','Beden Eğitimi',1,1,'Güz',2,0,2,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(4,'BP104','Grafik ve Animasyon',1,1,'Güz',3,1,2,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(5,'BP105','Matematik',1,1,'Güz',3,3,0,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(6,'BP106','Programlama Temelleri',1,1,'Güz',4,2,2,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(7,'BP107','Temel Yabancı Dil (İngilizce)',1,1,'Güz',2,2,0,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(8,'BP108','Türk Dili I',1,1,'Güz',2,2,0,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(9,'BP109','Web Tasarım Temelleri',1,1,'Güz',3,1,2,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(10,'BP201','Bilgisayar Destekli Çizim',1,2,'Güz',3,1,2,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(11,'BP202','Görüntü İşlemenin Temelleri',1,2,'Güz',3,2,1,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(12,'BP203','İşletme Yönetimi I',1,2,'Güz',2,2,0,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(13,'BP204','Mikrobilgisayar ve Assembler',1,2,'Güz',3,2,1,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(14,'BP205','Nesne Tabanlı Programlama',1,2,'Güz',4,2,2,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(15,'BP206','Sunucu İşletim Sistemleri',1,2,'Güz',3,1,2,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(16,'BP207','Temel Yabancı Dil (İngilizce)',1,2,'Güz',2,2,0,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(17,'BP208','Veri Tabanı Uygulamaları',1,2,'Güz',4,2,2,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(18,'BP209','Dönem Projesi',1,2,'Güz',2,0,2,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(19,'ET101','Doğru Akım Devre Analizi',2,1,'Güz',3,2,1,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(20,'ET102','Mikrobilgisayar ve Assembler',2,1,'Güz',3,2,1,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(21,'ET103','Sayısal Elektronik',2,1,'Güz',3,2,1,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(22,'ET104','Temel Elektronik',2,1,'Güz',3,2,1,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(23,'ET105','Türk Dili I',2,1,'Güz',2,2,0,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(24,'ET106','Temel Yabancı Dil (İngilizce)',2,1,'Güz',2,2,0,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(25,'ET107','Bilgisayar Destekli Çizim',2,1,'Güz',3,1,2,0,2,3,1,1,'2025-09-25 02:42:07','2025-09-25 02:42:07');
/*!40000 ALTER TABLE `dersler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `derslikler`
--

DROP TABLE IF EXISTS `derslikler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `derslikler` (
  `derslik_id` int(11) NOT NULL AUTO_INCREMENT,
  `derslik_kodu` varchar(10) NOT NULL,
  `derslik_adi_tr` varchar(100) NOT NULL,
  `derslik_adi_en` varchar(100) DEFAULT NULL,
  `kapasite` int(11) DEFAULT 30,
  `tur` enum('Derslik','Laboratuvar','Konferans','Diğer') DEFAULT 'Derslik',
  `donanim` text DEFAULT NULL,
  `bina` varchar(50) DEFAULT NULL,
  `kat` varchar(10) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`derslik_id`),
  UNIQUE KEY `derslik_kodu` (`derslik_kodu`),
  KEY `idx_derslik_kodu` (`derslik_kodu`),
  KEY `idx_tur` (`tur`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `derslikler`
--

LOCK TABLES `derslikler` WRITE;
/*!40000 ALTER TABLE `derslikler` DISABLE KEYS */;
INSERT INTO `derslikler` VALUES (1,'D1','Derslik 1','Classroom 1',30,'Derslik','Projeksiyon, Akıllı Tahta',NULL,NULL,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(2,'D2','Derslik 2','Classroom 2',30,'Derslik','Projeksiyon, Akıllı Tahta',NULL,NULL,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(3,'BL1','Bilgisayar Laboratuvarı 1','Computer Laboratory 1',25,'Laboratuvar','25 Bilgisayar, Projeksiyon',NULL,NULL,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(4,'BL2','Bilgisayar Laboratuvarı 2','Computer Laboratory 2',25,'Laboratuvar','25 Bilgisayar, Projeksiyon',NULL,NULL,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(5,'EL','Elektronik Laboratuvarı','Electronics Laboratory',20,'Laboratuvar','Elektronik Deney Setleri',NULL,NULL,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(6,'HPL','Hidrolik ve Pnömatik Laboratuvarı','Hydraulics and Pneumatics Laboratory',20,'Laboratuvar','Hidrolik Deney Setleri',NULL,NULL,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(7,'KS','Konferans Salonu','Conference Hall',100,'Konferans','Projeksiyon, Ses Sistemi',NULL,NULL,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(8,'DO','Danışman Odası','Advisor Office',10,'Diğer','Masa, Sandalye',NULL,NULL,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(9,'YB','Yerleşke Bahçesi','Campus Garden',50,'Diğer','Açık Alan',NULL,NULL,1,'2025-09-25 02:42:07','2025-09-25 02:42:07');
/*!40000 ALTER TABLE `derslikler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `haftalik_program`
--

DROP TABLE IF EXISTS `haftalik_program`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `haftalik_program` (
  `program_id` int(11) NOT NULL AUTO_INCREMENT,
  `atama_id` int(11) NOT NULL,
  `gun` tinyint(4) NOT NULL CHECK (`gun` between 1 and 5),
  `baslangic_saat` time NOT NULL,
  `bitis_saat` time NOT NULL,
  `derslik_id` int(11) NOT NULL,
  `hafta_tipi` enum('Her Hafta','Tek Hafta','Çift Hafta') DEFAULT 'Her Hafta',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`program_id`),
  KEY `atama_id` (`atama_id`),
  KEY `idx_gun_saat` (`gun`,`baslangic_saat`),
  KEY `idx_derslik_zaman` (`derslik_id`,`gun`,`baslangic_saat`),
  KEY `idx_hp_composite` (`gun`,`baslangic_saat`,`derslik_id`),
  CONSTRAINT `haftalik_program_ibfk_1` FOREIGN KEY (`atama_id`) REFERENCES `ders_atamalari` (`atama_id`) ON DELETE CASCADE,
  CONSTRAINT `haftalik_program_ibfk_2` FOREIGN KEY (`derslik_id`) REFERENCES `derslikler` (`derslik_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `haftalik_program`
--

LOCK TABLES `haftalik_program` WRITE;
/*!40000 ALTER TABLE `haftalik_program` DISABLE KEYS */;
INSERT INTO `haftalik_program` VALUES (1,1,1,'08:30:00','10:00:00',1,'Her Hafta','2025-09-25 02:42:08','2025-09-25 02:42:08'),(2,2,1,'10:00:00','11:30:00',2,'Her Hafta','2025-09-25 02:42:08','2025-09-25 02:42:08'),(3,3,2,'14:00:00','16:00:00',3,'Her Hafta','2025-09-25 02:42:08','2025-09-25 02:42:08'),(4,4,3,'09:00:00','10:30:00',1,'Her Hafta','2025-09-25 02:42:08','2025-09-25 02:42:08'),(5,5,4,'14:30:00','16:00:00',2,'Her Hafta','2025-09-25 02:42:08','2025-09-25 02:42:08');
/*!40000 ALTER TABLE `haftalik_program` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER trg_kisit_kontrol_before_insert
BEFORE INSERT ON haftalik_program
FOR EACH ROW
BEGIN
    DECLARE v_kisit_sayisi INT;
    DECLARE v_ogretmen_id INT;
    DECLARE v_hata_mesaji VARCHAR(255);
    
    -- Öğretmen ID'sini al
    SELECT ogretmen_id INTO v_ogretmen_id
    FROM ders_atamalari
    WHERE atama_id = NEW.atama_id;
    
    -- Kısıt kontrolü
    SELECT COUNT(*) INTO v_kisit_sayisi
    FROM kisitlamalar
    WHERE ogretmen_id = v_ogretmen_id
    AND gun = NEW.gun
    AND aktif = TRUE
    AND (
        (kisit_tipi = 'once' AND NEW.baslangic_saat < baslangic_saat) OR
        (kisit_tipi = 'sonra' AND NEW.bitis_saat > bitis_saat) OR
        (kisit_tipi = 'tam' AND NEW.baslangic_saat <= baslangic_saat AND NEW.bitis_saat >= bitis_saat) OR
        (kisit_tipi = 'aralik' AND NEW.baslangic_saat >= baslangic_saat AND NEW.bitis_saat <= bitis_saat)
    );
    
    IF v_kisit_sayisi > 0 THEN
        SET v_hata_mesaji = CONCAT('Öğretmen kısıtı ihlali: Gün ', NEW.gun, ', Saat ', NEW.baslangic_saat);
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_hata_mesaji;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER trg_cakisma_log_after_insert
AFTER INSERT ON haftalik_program
FOR EACH ROW
BEGIN
    DECLARE v_ogretmen_cakisma INT;
    DECLARE v_derslik_cakisma INT;
    DECLARE v_ogretmen_id INT;
    
    -- Öğretmen ID'sini al
    SELECT ogretmen_id INTO v_ogretmen_id
    FROM ders_atamalari
    WHERE atama_id = NEW.atama_id;
    
    -- Öğretmen çakışması kontrolü
    SELECT COUNT(*) INTO v_ogretmen_cakisma
    FROM haftalik_program hp
    JOIN ders_atamalari da ON hp.atama_id = da.atama_id
    WHERE da.ogretmen_id = v_ogretmen_id
    AND hp.gun = NEW.gun
    AND hp.baslangic_saat < NEW.bitis_saat 
    AND hp.bitis_saat > NEW.baslangic_saat
    AND hp.program_id != NEW.program_id;
    
    IF v_ogretmen_cakisma > 0 THEN
        INSERT INTO cakisma_loglari (tur, kaynak_id, gun, saat, detay)
        VALUES ('Öğretmen', v_ogretmen_id, NEW.gun, NEW.baslangic_saat, 
                JSON_OBJECT('derslik_id', NEW.derslik_id, 'atama_id', NEW.atama_id));
    END IF;
    
    -- Derslik çakışması kontrolü
    SELECT COUNT(*) INTO v_derslik_cakisma
    FROM haftalik_program
    WHERE derslik_id = NEW.derslik_id
    AND gun = NEW.gun
    AND baslangic_saat < NEW.bitis_saat 
    AND bitis_saat > NEW.baslangic_saat
    AND program_id != NEW.program_id;
    
    IF v_derslik_cakisma > 0 THEN
        INSERT INTO cakisma_loglari (tur, kaynak_id, gun, saat, detay)
        VALUES ('Derslik', NEW.derslik_id, NEW.gun, NEW.baslangic_saat, 
                JSON_OBJECT('ogretmen_id', v_ogretmen_id, 'atama_id', NEW.atama_id));
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `kisitlamalar`
--

DROP TABLE IF EXISTS `kisitlamalar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kisitlamalar` (
  `kisit_id` int(11) NOT NULL AUTO_INCREMENT,
  `ogretmen_id` int(11) DEFAULT NULL,
  `gun` tinyint(4) DEFAULT NULL,
  `baslangic_saat` time DEFAULT NULL,
  `bitis_saat` time DEFAULT NULL,
  `kisit_tipi` enum('once','sonra','tam','aralik') NOT NULL,
  `aciklama` text DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`kisit_id`),
  KEY `idx_ogretmen_kisit` (`ogretmen_id`,`gun`),
  CONSTRAINT `kisitlamalar_ibfk_1` FOREIGN KEY (`ogretmen_id`) REFERENCES `ogretim_elemanlari` (`ogretmen_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kisitlamalar`
--

LOCK TABLES `kisitlamalar` WRITE;
/*!40000 ALTER TABLE `kisitlamalar` DISABLE KEYS */;
/*!40000 ALTER TABLE `kisitlamalar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ogretim_elemanlari`
--

DROP TABLE IF EXISTS `ogretim_elemanlari`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ogretim_elemanlari` (
  `ogretmen_id` int(11) NOT NULL AUTO_INCREMENT,
  `kisa_ad` varchar(10) NOT NULL,
  `tam_ad` varchar(100) DEFAULT NULL,
  `unvan` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `renk_kodu` varchar(7) DEFAULT '#667eea',
  `desen_tipi` enum('dots','stripes') DEFAULT 'dots',
  `haftalik_ders_limiti` int(11) DEFAULT 20,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ogretmen_id`),
  UNIQUE KEY `kisa_ad` (`kisa_ad`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_kisa_ad` (`kisa_ad`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ogretim_elemanlari`
--

LOCK TABLES `ogretim_elemanlari` WRITE;
/*!40000 ALTER TABLE `ogretim_elemanlari` DISABLE KEYS */;
INSERT INTO `ogretim_elemanlari` VALUES (1,'A.A.','Ali Aydın','Öğr. Gör.','a.aydin@okul.edu.tr',NULL,'#FF6B6B','dots',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(2,'E.A.','Elif Arslan','Öğr. Gör.','e.arslan@okul.edu.tr',NULL,'#95E1D3','stripes',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(3,'E.Y.','Emre Yılmaz','Öğr. Gör.','e.yilmaz@okul.edu.tr',NULL,'#A8E6CF','dots',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(4,'M.D.','Mehmet Demir','Öğr. Gör. Dr.','m.demir@okul.edu.tr',NULL,'#FFAAA5','stripes',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(5,'M.A.','Murat Aksoy','Öğr. Gör.','m.aksoy@okul.edu.tr',NULL,'#8FCACA','dots',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(6,'Mu.D.','Mustafa Doğan','Öğr. Gör.','mu.dogan@okul.edu.tr',NULL,'#FFC0CB','stripes',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(7,'O.K.Ş.','Osman Kaan Şahin','Öğr. Gör.','o.sahin@okul.edu.tr',NULL,'#B5EAD7','dots',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(8,'S.B.','Selin Başar','Öğr. Gör.','s.basar@okul.edu.tr',NULL,'#FFD93D','stripes',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(9,'S.E.','Serkan Erdoğan','Öğr. Gör.','s.erdogan@okul.edu.tr',NULL,'#FFA8A8','dots',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(10,'S.D.','Seda Demirci','Öğr. Gör.','s.demirci@okul.edu.tr',NULL,'#B4E7CE','stripes',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(11,'T.D.','Tolga Duran','Öğr. Gör.','t.duran@okul.edu.tr',NULL,'#F5B7B1','dots',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(12,'U.T.','Umut Tekin','Öğr. Gör.','u.tekin@okul.edu.tr',NULL,'#AED6F1','stripes',20,1,'2025-09-25 02:42:07','2025-09-25 02:42:07');
/*!40000 ALTER TABLE `ogretim_elemanlari` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programlar`
--

DROP TABLE IF EXISTS `programlar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programlar` (
  `program_id` int(11) NOT NULL AUTO_INCREMENT,
  `program_kodu` varchar(10) NOT NULL,
  `program_adi` varchar(100) NOT NULL,
  `bolum` varchar(50) NOT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`program_id`),
  UNIQUE KEY `program_kodu` (`program_kodu`),
  KEY `idx_program_kodu` (`program_kodu`),
  KEY `idx_bolum` (`bolum`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programlar`
--

LOCK TABLES `programlar` WRITE;
/*!40000 ALTER TABLE `programlar` DISABLE KEYS */;
INSERT INTO `programlar` VALUES (1,'BP','Bilgisayar Programcılığı','Bilgisayar Teknolojileri',1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(2,'ET','Elektronik Teknolojisi','Elektrik ve Enerji',1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(3,'EE','Elektrik Enerjisi Üretim İletim Dağıtım','Elektrik ve Enerji',1,'2025-09-25 02:42:07','2025-09-25 02:42:07'),(4,'YE','Yenilenebilir Enerji Teknikerliği','Elektrik ve Enerji',1,'2025-09-25 02:42:07','2025-09-25 02:42:07');
/*!40000 ALTER TABLE `programlar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saat_dilimleri`
--

DROP TABLE IF EXISTS `saat_dilimleri`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saat_dilimleri` (
  `slot_id` int(11) NOT NULL AUTO_INCREMENT,
  `baslangic` time NOT NULL,
  `bitis` time NOT NULL,
  `slot_no` int(11) NOT NULL,
  `ogle_arasi` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`slot_id`),
  UNIQUE KEY `unique_slot` (`baslangic`,`bitis`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saat_dilimleri`
--

LOCK TABLES `saat_dilimleri` WRITE;
/*!40000 ALTER TABLE `saat_dilimleri` DISABLE KEYS */;
INSERT INTO `saat_dilimleri` VALUES (1,'08:30:00','09:00:00',1,0),(2,'09:00:00','09:30:00',2,0),(3,'09:30:00','10:00:00',3,0),(4,'10:00:00','10:30:00',4,0),(5,'10:30:00','11:00:00',5,0),(6,'11:00:00','11:30:00',6,0),(7,'11:30:00','12:00:00',7,0),(8,'12:00:00','12:30:00',8,0),(9,'12:30:00','14:00:00',9,1),(10,'14:00:00','14:30:00',10,0),(11,'14:30:00','15:00:00',11,0),(12,'15:00:00','15:30:00',12,0),(13,'15:30:00','16:00:00',13,0),(14,'16:00:00','16:30:00',14,0),(15,'16:30:00','17:00:00',15,0),(16,'17:00:00','17:30:00',16,0),(17,'17:30:00','18:00:00',17,0);
/*!40000 ALTER TABLE `saat_dilimleri` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `v_derslik_doluluk`
--

DROP TABLE IF EXISTS `v_derslik_doluluk`;
/*!50001 DROP VIEW IF EXISTS `v_derslik_doluluk`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_derslik_doluluk` AS SELECT
 1 AS `derslik_kodu`,
  1 AS `derslik_adi_tr`,
  1 AS `gun`,
  1 AS `baslangic_saat`,
  1 AS `bitis_saat`,
  1 AS `ders_adi`,
  1 AS `program_adi`,
  1 AS `ogretmen` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_haftalik_program`
--

DROP TABLE IF EXISTS `v_haftalik_program`;
/*!50001 DROP VIEW IF EXISTS `v_haftalik_program`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_haftalik_program` AS SELECT
 1 AS `program_id`,
  1 AS `program_adi`,
  1 AS `ders_adi`,
  1 AS `sinif`,
  1 AS `ogretmen`,
  1 AS `derslik`,
  1 AS `gun`,
  1 AS `baslangic_saat`,
  1 AS `bitis_saat` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_ogretmen_ders_yuku`
--

DROP TABLE IF EXISTS `v_ogretmen_ders_yuku`;
/*!50001 DROP VIEW IF EXISTS `v_ogretmen_ders_yuku`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_ogretmen_ders_yuku` AS SELECT
 1 AS `kisa_ad`,
  1 AS `tam_ad`,
  1 AS `ders_sayisi`,
  1 AS `toplam_saat`,
  1 AS `haftalik_ders_limiti`,
  1 AS `bos_saat` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'ders_programi'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_bos_derslikler` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE  PROCEDURE `sp_bos_derslikler`(
    IN p_gun TINYINT,
    IN p_baslangic TIME,
    IN p_bitis TIME
)
BEGIN
    SELECT 
        d.derslik_kodu,
        d.derslik_adi_tr,
        d.kapasite,
        d.tur
    FROM derslikler d
    WHERE d.aktif = TRUE
    AND d.derslik_id NOT IN (
        SELECT DISTINCT hp.derslik_id
        FROM haftalik_program hp
        WHERE hp.gun = p_gun
        AND hp.baslangic_saat < p_bitis 
        AND hp.bitis_saat > p_baslangic
    )
    ORDER BY d.tur, d.kapasite DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_cakisma_kontrolu` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE  PROCEDURE `sp_cakisma_kontrolu`(
    IN p_gun TINYINT,
    IN p_baslangic TIME,
    IN p_bitis TIME
)
BEGIN
    -- Öğretmen çakışmaları
    SELECT 
        'Öğretmen Çakışması' AS cakisma_turu,
        oe.kisa_ad AS kaynak,
        GROUP_CONCAT(DISTINCT p.program_adi) AS programlar,
        GROUP_CONCAT(DISTINCT d.ders_adi) AS dersler
    FROM haftalik_program hp1
    JOIN haftalik_program hp2 ON hp1.atama_id != hp2.atama_id
    JOIN ders_atamalari da1 ON hp1.atama_id = da1.atama_id
    JOIN ders_atamalari da2 ON hp2.atama_id = da2.atama_id
    JOIN ogretim_elemanlari oe ON da1.ogretmen_id = oe.ogretmen_id
    JOIN dersler d ON da1.ders_id = d.ders_id
    JOIN programlar p ON d.program_id = p.program_id
    WHERE da1.ogretmen_id = da2.ogretmen_id
    AND hp1.gun = p_gun AND hp2.gun = p_gun
    AND ((hp1.baslangic_saat < p_bitis AND hp1.bitis_saat > p_baslangic)
        OR (hp2.baslangic_saat < p_bitis AND hp2.bitis_saat > p_baslangic))
    GROUP BY oe.ogretmen_id
    
    UNION ALL
    
    -- Derslik çakışmaları
    SELECT 
        'Derslik Çakışması' AS cakisma_turu,
        dr.derslik_adi_tr AS kaynak,
        GROUP_CONCAT(DISTINCT p.program_adi) AS programlar,
        GROUP_CONCAT(DISTINCT d.ders_adi) AS dersler
    FROM haftalik_program hp
    JOIN derslikler dr ON hp.derslik_id = dr.derslik_id
    JOIN ders_atamalari da ON hp.atama_id = da.atama_id
    JOIN dersler d ON da.ders_id = d.ders_id
    JOIN programlar p ON d.program_id = p.program_id
    WHERE hp.gun = p_gun
    AND hp.baslangic_saat < p_bitis 
    AND hp.bitis_saat > p_baslangic
    GROUP BY dr.derslik_id
    HAVING COUNT(*) > 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ders_atama_ekle` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE  PROCEDURE `sp_ders_atama_ekle`(
    IN p_ders_id INT,
    IN p_ogretmen_id INT,
    IN p_gun TINYINT,
    IN p_baslangic TIME,
    IN p_bitis TIME,
    IN p_derslik_id INT,
    OUT p_sonuc VARCHAR(255)
)
BEGIN
    DECLARE v_atama_id INT;
    DECLARE v_cakisma_sayisi INT;
    DECLARE v_aktif_donem INT;
    
    -- Aktif dönemi al
    SELECT donem_id INTO v_aktif_donem 
    FROM akademik_donemler 
    WHERE aktif = TRUE 
    LIMIT 1;
    
    -- Çakışma kontrolü
    SELECT COUNT(*) INTO v_cakisma_sayisi
    FROM haftalik_program hp
    JOIN ders_atamalari da ON hp.atama_id = da.atama_id
    WHERE hp.gun = p_gun
    AND hp.baslangic_saat < p_bitis 
    AND hp.bitis_saat > p_baslangic
    AND (da.ogretmen_id = p_ogretmen_id OR hp.derslik_id = p_derslik_id);
    
    IF v_cakisma_sayisi > 0 THEN
        SET p_sonuc = 'HATA: Çakışma tespit edildi!';
    ELSE
        -- Ders ataması ekle
        INSERT INTO ders_atamalari (ders_id, ogretmen_id, donem_id)
        VALUES (p_ders_id, p_ogretmen_id, v_aktif_donem);
        
        SET v_atama_id = LAST_INSERT_ID();
        
        -- Haftalık programa ekle
        INSERT INTO haftalik_program (atama_id, gun, baslangic_saat, bitis_saat, derslik_id)
        VALUES (v_atama_id, p_gun, p_baslangic, p_bitis, p_derslik_id);
        
        SET p_sonuc = 'BAŞARILI: Ders ataması eklendi.';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `v_derslik_doluluk`
--

/*!50001 DROP VIEW IF EXISTS `v_derslik_doluluk`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_derslik_doluluk` AS select `dr`.`derslik_kodu` AS `derslik_kodu`,`dr`.`derslik_adi_tr` AS `derslik_adi_tr`,`hp`.`gun` AS `gun`,`hp`.`baslangic_saat` AS `baslangic_saat`,`hp`.`bitis_saat` AS `bitis_saat`,`d`.`ders_adi` AS `ders_adi`,`p`.`program_adi` AS `program_adi`,`oe`.`kisa_ad` AS `ogretmen` from (((((`derslikler` `dr` left join `haftalik_program` `hp` on(`dr`.`derslik_id` = `hp`.`derslik_id`)) left join `ders_atamalari` `da` on(`hp`.`atama_id` = `da`.`atama_id`)) left join `dersler` `d` on(`da`.`ders_id` = `d`.`ders_id`)) left join `programlar` `p` on(`d`.`program_id` = `p`.`program_id`)) left join `ogretim_elemanlari` `oe` on(`da`.`ogretmen_id` = `oe`.`ogretmen_id`)) order by `dr`.`derslik_kodu`,`hp`.`gun`,`hp`.`baslangic_saat` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_haftalik_program`
--

/*!50001 DROP VIEW IF EXISTS `v_haftalik_program`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_haftalik_program` AS select `hp`.`program_id` AS `program_id`,`p`.`program_adi` AS `program_adi`,`d`.`ders_adi` AS `ders_adi`,`d`.`sinif` AS `sinif`,`oe`.`kisa_ad` AS `ogretmen`,`dr`.`derslik_adi_tr` AS `derslik`,case `hp`.`gun` when 1 then 'Pazartesi' when 2 then 'Salı' when 3 then 'Çarşamba' when 4 then 'Perşembe' when 5 then 'Cuma' end AS `gun`,`hp`.`baslangic_saat` AS `baslangic_saat`,`hp`.`bitis_saat` AS `bitis_saat` from (((((`haftalik_program` `hp` join `ders_atamalari` `da` on(`hp`.`atama_id` = `da`.`atama_id`)) join `dersler` `d` on(`da`.`ders_id` = `d`.`ders_id`)) join `programlar` `p` on(`d`.`program_id` = `p`.`program_id`)) join `ogretim_elemanlari` `oe` on(`da`.`ogretmen_id` = `oe`.`ogretmen_id`)) join `derslikler` `dr` on(`hp`.`derslik_id` = `dr`.`derslik_id`)) where `da`.`donem_id` = (select `akademik_donemler`.`donem_id` from `akademik_donemler` where `akademik_donemler`.`aktif` = 1 limit 1) order by `p`.`program_id`,`d`.`sinif`,`hp`.`gun`,`hp`.`baslangic_saat` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_ogretmen_ders_yuku`
--

/*!50001 DROP VIEW IF EXISTS `v_ogretmen_ders_yuku`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_ogretmen_ders_yuku` AS select `oe`.`kisa_ad` AS `kisa_ad`,`oe`.`tam_ad` AS `tam_ad`,count(distinct `da`.`ders_id`) AS `ders_sayisi`,sum(`d`.`haftalik_saat`) AS `toplam_saat`,`oe`.`haftalik_ders_limiti` AS `haftalik_ders_limiti`,`oe`.`haftalik_ders_limiti` - sum(`d`.`haftalik_saat`) AS `bos_saat` from ((`ogretim_elemanlari` `oe` left join `ders_atamalari` `da` on(`oe`.`ogretmen_id` = `da`.`ogretmen_id`)) left join `dersler` `d` on(`da`.`ders_id` = `d`.`ders_id`)) where `da`.`donem_id` = (select `akademik_donemler`.`donem_id` from `akademik_donemler` where `akademik_donemler`.`aktif` = 1 limit 1) group by `oe`.`ogretmen_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-25  7:08:36
