-- MySQL dump 10.13  Distrib 8.0.33, for Linux (x86_64)
--
-- Host: localhost    Database: db_perpustakaan
-- ------------------------------------------------------
-- Server version	8.0.33-0ubuntu0.22.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `anggota`
--

DROP TABLE IF EXISTS `anggota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode` char(5) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` char(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggota`
--

LOCK TABLES `anggota` WRITE;
/*!40000 ALTER TABLE `anggota` DISABLE KEYS */;
INSERT INTO `anggota` VALUES (1,'A01','Muhammad Pauzi UPDATED','Alamat','028127873'),(3,'A02','Anggota Baru','Alamat Anggota Baru','0912982938');
/*!40000 ALTER TABLE `anggota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buku`
--

DROP TABLE IF EXISTS `buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buku` (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `penulis` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `penerbit` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `cover` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buku`
--

LOCK TABLES `buku` WRITE;
/*!40000 ALTER TABLE `buku` DISABLE KEYS */;
INSERT INTO `buku` VALUES (6,'Buku #1 UPDATED','Penulis Buku #1','Penerbit Buku','img/1.1688123041.jpeg','-'),(7,'Buku #2','Penulis Buku #1','Penerbit Buku','img/buku-dahlan.1688123071.jpg','-');
/*!40000 ALTER TABLE `buku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buku_transaksi_peminjaman`
--

DROP TABLE IF EXISTS `buku_transaksi_peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buku_transaksi_peminjaman` (
  `id` int NOT NULL AUTO_INCREMENT,
  `buku_id` int NOT NULL,
  `transaksi_peminjaman_id` int NOT NULL,
  `jml_buku` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `buku_id` (`buku_id`) USING BTREE,
  KEY `transaksi_peminjaman_id` (`transaksi_peminjaman_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buku_transaksi_peminjaman`
--

LOCK TABLES `buku_transaksi_peminjaman` WRITE;
/*!40000 ALTER TABLE `buku_transaksi_peminjaman` DISABLE KEYS */;
INSERT INTO `buku_transaksi_peminjaman` VALUES (1,6,1,1),(2,7,1,2);
/*!40000 ALTER TABLE `buku_transaksi_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi_peminjaman`
--

DROP TABLE IF EXISTS `transaksi_peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi_peminjaman` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dipinjam_pada` datetime NOT NULL,
  `jatuh_tempo_pada` date NOT NULL,
  `dikembalikan_pada` datetime DEFAULT NULL,
  `denda` bigint NOT NULL DEFAULT '0',
  `keterangan` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `operator_id` int NOT NULL,
  `anggota_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`),
  KEY `anggota_id` (`anggota_id`) USING BTREE,
  KEY `operator_id` (`operator_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi_peminjaman`
--

LOCK TABLES `transaksi_peminjaman` WRITE;
/*!40000 ALTER TABLE `transaksi_peminjaman` DISABLE KEYS */;
INSERT INTO `transaksi_peminjaman` VALUES (1,'TRS-3006230607-649E','2023-06-30 18:07:00','2023-07-01','2023-06-30 18:07:00',20000,'-',1,1);
/*!40000 ALTER TABLE `transaksi_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Muhammad Pauzi UPDATED','$2y$10$S780GiAzLAZMZJnWjWDGpuA2GlhLV7Zsz.hZjHv/rNQu5jgD7fF0a','muhammadpauzi'),(7,'User Baru #2','$2y$10$tTX9CtrMg97D2e5owQBQceH/V6oZ2gDbkRAIM4tjlxXLtzqgvmD9m','muhammadpauzi2');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-07-26 17:35:46
