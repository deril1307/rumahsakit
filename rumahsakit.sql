-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for rumahsakit
CREATE DATABASE IF NOT EXISTS `rumahsakit` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `rumahsakit`;

-- Dumping structure for table rumahsakit.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table rumahsakit.jadwals
CREATE TABLE IF NOT EXISTS `jadwals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pasien_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `jenis_terapi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('terjadwal','selesai','batal','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'terjadwal',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwals_pasien_id_foreign` (`pasien_id`),
  KEY `jadwals_tanggal_index` (`tanggal`),
  KEY `jadwals_status_index` (`status`),
  KEY `jadwals_user_id_tanggal_index` (`user_id`,`tanggal`),
  CONSTRAINT `jadwals_pasien_id_foreign` FOREIGN KEY (`pasien_id`) REFERENCES `pasiens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.jadwals: ~2 rows (approximately)
INSERT INTO `jadwals` (`id`, `pasien_id`, `user_id`, `jenis_terapi`, `tanggal`, `jam_mulai`, `jam_selesai`, `ruangan`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
	(5, 1, 7, 'Fisioterapi', '2025-12-11', '16:08:00', '17:09:00', 'TULT', 'terjadwal', NULL, '2025-12-01 23:05:04', '2025-12-01 23:16:04'),
	(6, 3, 13, 'Fisioterapi', '2025-12-23', '13:40:00', '17:24:00', 'TULT', 'terjadwal', NULL, '2025-12-02 05:40:24', '2025-12-02 05:40:33');

-- Dumping structure for table rumahsakit.jenis_terapis
CREATE TABLE IF NOT EXISTS `jenis_terapis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_terapi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jenis_terapis_nama_terapi_unique` (`nama_terapi`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.jenis_terapis: ~4 rows (approximately)
INSERT INTO `jenis_terapis` (`id`, `nama_terapi`, `created_at`, `updated_at`) VALUES
	(1, 'Fisioterapi', '2025-12-01 09:50:42', '2025-12-01 09:50:42'),
	(2, 'Terapi Okupasi', '2025-12-01 09:50:42', '2025-12-01 09:50:42'),
	(3, 'Terapi Wicara', '2025-12-01 09:50:42', '2025-12-01 09:50:42'),
	(4, 'Psikologi', '2025-12-01 09:50:42', '2025-12-01 09:50:42');

-- Dumping structure for table rumahsakit.ketersediaan_terapis
CREATE TABLE IF NOT EXISTS `ketersediaan_terapis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `terapis_id` bigint unsigned NOT NULL,
  `hari` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `is_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ketersediaan_terapis_terapis_id_hari_jam_mulai_unique` (`terapis_id`,`hari`,`jam_mulai`),
  CONSTRAINT `ketersediaan_terapis_terapis_id_foreign` FOREIGN KEY (`terapis_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.ketersediaan_terapis: ~0 rows (approximately)

-- Dumping structure for table rumahsakit.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.migrations: ~14 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2025_11_05_074036_create_permission_tables', 2),
	(6, '2025_11_17_170047_add_terapis_fields_to_users_table', 3),
	(7, '2025_11_17_171305_add_nip_to_users_table', 4),
	(8, '2025_11_19_073224_create_pasiens_table', 5),
	(9, '2025_11_25_121411_create_jenis_terapi_table', 6),
	(10, '2025_11_25_121928_create_jadwal_terapi_table', 6),
	(11, '2025_11_25_121935_create_ketersediaan_terapis_table', 6),
	(12, '2025_11_25_121940_add_scheduling_fields_to_pasiens_table', 6),
	(13, '2025_12_01_162149_create_jadwals_table', 7),
	(14, '2025_12_01_164318_create_jenis_terapis_table', 7);

-- Dumping structure for table rumahsakit.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table rumahsakit.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.model_has_roles: ~6 rows (approximately)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(1, 'App\\Models\\User', 5),
	(3, 'App\\Models\\User', 6),
	(2, 'App\\Models\\User', 7),
	(2, 'App\\Models\\User', 13),
	(2, 'App\\Models\\User', 14);

-- Dumping structure for table rumahsakit.pasiens
CREATE TABLE IF NOT EXISTS `pasiens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_rm` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenis_kelamin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `no_telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `riwayat_medis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `jenis_pembayaran` enum('BPJS','Umum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Umum',
  `tanggal_periksa_dokter` date DEFAULT NULL COMMENT 'Tanggal rujukan dari dr. SPKFR',
  `no_rujukan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `masa_berlaku_rujukan` date DEFAULT NULL COMMENT 'Tanggal expired rujukan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pasiens_no_rm_unique` (`no_rm`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.pasiens: ~3 rows (approximately)
INSERT INTO `pasiens` (`id`, `nama`, `no_rm`, `tgl_lahir`, `jenis_kelamin`, `alamat`, `no_telp`, `riwayat_medis`, `status`, `jenis_pembayaran`, `tanggal_periksa_dokter`, `no_rujukan`, `masa_berlaku_rujukan`, `created_at`, `updated_at`) VALUES
	(1, 'Aryo Pradipta', '012345', '2003-06-20', 'Laki-laki', 'Jl Babakan Ciamis', '083815903215', 'Alergi Hidung', 'Aktif', 'Umum', NULL, NULL, NULL, '2025-11-19 00:39:13', '2025-11-21 23:55:22'),
	(2, 'SEHAT WALAFIA', '012348', '2025-11-04', 'Laki-laki', 'asdasd', '0895335176737', 'dfsdfsdfsfsfsdfsdfs', 'Aktif', 'Umum', NULL, NULL, NULL, '2025-11-24 08:39:06', '2025-11-24 08:39:06'),
	(3, 'Giovanni Nadika', '012333', '2016-02-02', 'Laki-laki', 'DASDAS', '0834312312312', 'segat', 'Aktif', 'Umum', NULL, NULL, NULL, '2025-12-01 11:29:04', '2025-12-01 11:29:04');

-- Dumping structure for table rumahsakit.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table rumahsakit.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.permissions: ~4 rows (approximately)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'buat-jadwal', 'web', '2025-11-05 01:01:17', '2025-11-05 01:01:17'),
	(2, 'update-status-pasien', 'web', '2025-11-05 01:01:17', '2025-11-05 01:01:17'),
	(3, 'lihat-laporan', 'web', '2025-11-05 01:01:17', '2025-11-05 01:01:17'),
	(4, 'kelola-user', 'web', '2025-11-05 01:01:17', '2025-11-05 01:01:17');

-- Dumping structure for table rumahsakit.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table rumahsakit.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.roles: ~3 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'web', '2025-11-05 01:01:17', '2025-11-05 01:01:17'),
	(2, 'terapis', 'web', '2025-11-05 01:01:17', '2025-11-05 01:01:17'),
	(3, 'kepala', 'web', '2025-11-05 01:01:17', '2025-11-05 01:01:17');

-- Dumping structure for table rumahsakit.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.role_has_permissions: ~6 rows (approximately)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(2, 2),
	(3, 3);

-- Dumping structure for table rumahsakit.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `spesialisasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rumahsakit.users: ~6 rows (approximately)
INSERT INTO `users` (`id`, `name`, `nip`, `email`, `spesialisasi`, `no_telp`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Deril Wijdan Falih', '12345', 'derilwijdan346@gmail.com', NULL, '083815903215', 'Aktif', NULL, '$2y$12$BoexXpXzXhrfvzq7jQ42IOE5pTSO4C23Xb/PeCpGVTqcDqQS1VrB.', 'Tz4b0x55HAm4zmxhZDRIE7mhYVBPQTK7IdP5cwE0nJFjwM63MJYn2APHlaOh', '2025-11-05 00:14:22', '2025-11-05 01:26:53'),
	(5, 'Shidqul Aziz lababan', '12345', 'shidqul@gmail.com', NULL, NULL, 'Aktif', NULL, '$2y$12$vJaHFwpt2rD1hpXzYWtk4u73OjcCnwwVS1rj86VV.OBOfaUAmOBU2', '9BORe7i66g2cDOFH5S66b1v95tGLdv4vlKCYku1sACZ7PkdovonaLafsVlgU', '2025-11-06 01:37:32', '2025-11-17 20:24:23'),
	(6, 'Giovanni Nadika', '12345', 'gio@gmail.com', NULL, NULL, 'Aktif', NULL, '$2y$12$ViSuvuPP261XySB/DVfbEubMouXRNPXdb3w1F7jQOrN0BmaeKdY0u', NULL, '2025-11-06 01:39:07', '2025-11-07 04:12:06'),
	(7, 'Bima Aditya Ramadhan', '12345', 'bima@gmail.com', 'Terapi Okupasi', '081234567810', 'Aktif', NULL, '$2y$12$IR7bkdyHKNbhhEjtIqk1nOqQvIm4ruxyJj.wBlif6GuXLFjYSWF.m', NULL, '2025-11-06 01:39:34', '2025-11-19 00:39:48'),
	(13, 'Hanif', '155555', 'hanif@gmail.com', 'Terapi Okupasi', '083815903215', 'Aktif', NULL, '$2y$12$wpgPz.Y1.DCi705.v4g8H.Zc5oQHWHDpGuJJEkvXxLu854E03BGNW', NULL, '2025-11-22 10:20:29', '2025-11-22 10:20:46'),
	(14, 'jaringan', '21312321', 'sdasdasdlkasdlaksdaslkd@gmail.com', 'Fisioterapi', '0895335176737', 'Aktif', NULL, '$2y$12$/eeVpCBp3zSuZUwVGoVE/uT5OLTBYG5EBiSKvqKplg6CZg3aFq7YS', NULL, '2025-11-24 08:58:07', '2025-11-24 09:51:58');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
