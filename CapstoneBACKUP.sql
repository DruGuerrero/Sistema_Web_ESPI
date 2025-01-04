-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         11.3.2-MariaDB-log - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla sistema_web_v5.careers
CREATE TABLE IF NOT EXISTS `careers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_moodle` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext DEFAULT NULL,
  `cant_estudiantes` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.careers: ~1 rows (aproximadamente)
DELETE FROM `careers`;
INSERT INTO `careers` (`id`, `id_moodle`, `nombre`, `descripcion`, `cant_estudiantes`, `created_at`, `updated_at`) VALUES
	(1, 37, 'Enfermería', 'Carrera de enfermería.', 1, '2024-10-15 05:26:05', '2024-10-15 05:32:14');

-- Volcando estructura para tabla sistema_web_v5.courses
CREATE TABLE IF NOT EXISTS `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_moodle` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext DEFAULT NULL,
  `id_docente` bigint(20) unsigned NOT NULL,
  `id_year` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courses_id_year_foreign` (`id_year`),
  KEY `courses_id_docente_foreign` (`id_docente`),
  CONSTRAINT `courses_id_docente_foreign` FOREIGN KEY (`id_docente`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `courses_id_year_foreign` FOREIGN KEY (`id_year`) REFERENCES `years` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.courses: ~2 rows (aproximadamente)
DELETE FROM `courses`;
INSERT INTO `courses` (`id`, `id_moodle`, `nombre`, `descripcion`, `id_docente`, `id_year`, `created_at`, `updated_at`) VALUES
	(1, 50, 'Historia de la medicina', 'Curso de historia de la MEDICINA.', 2, 1, '2024-10-15 05:28:56', '2024-10-15 05:29:44'),
	(2, 51, 'Historia de la Medicina II', 'curso de la medicina 2', 2, 2, '2024-10-15 05:33:26', '2024-10-15 05:33:26');

-- Volcando estructura para tabla sistema_web_v5.debts
CREATE TABLE IF NOT EXISTS `debts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_student` bigint(20) unsigned NOT NULL,
  `id_product` bigint(20) unsigned NOT NULL,
  `monto_pendiente` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `debts_id_student_foreign` (`id_student`),
  KEY `debts_id_product_foreign` (`id_product`),
  CONSTRAINT `debts_id_product_foreign` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `debts_id_student_foreign` FOREIGN KEY (`id_student`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.debts: ~1 rows (aproximadamente)
DELETE FROM `debts`;
INSERT INTO `debts` (`id`, `id_student`, `id_product`, `monto_pendiente`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 10.00, '2024-10-15 05:37:47', '2024-10-15 05:38:30');

-- Volcando estructura para tabla sistema_web_v5.enrollments
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_student` bigint(20) unsigned NOT NULL,
  `id_career` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enrollments_id_student_foreign` (`id_student`),
  KEY `enrollments_id_career_foreign` (`id_career`),
  CONSTRAINT `enrollments_id_career_foreign` FOREIGN KEY (`id_career`) REFERENCES `careers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_id_student_foreign` FOREIGN KEY (`id_student`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.enrollments: ~1 rows (aproximadamente)
DELETE FROM `enrollments`;
INSERT INTO `enrollments` (`id`, `id_student`, `id_career`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, '2024-10-15 05:31:14', '2024-10-15 05:31:14');

-- Volcando estructura para tabla sistema_web_v5.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.failed_jobs: ~0 rows (aproximadamente)
DELETE FROM `failed_jobs`;

-- Volcando estructura para tabla sistema_web_v5.media_files
CREATE TABLE IF NOT EXISTS `media_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `id_course` bigint(20) unsigned DEFAULT NULL,
  `id_career` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_files_student_id_foreign` (`student_id`),
  KEY `media_files_id_course_foreign` (`id_course`),
  KEY `media_files_id_career_foreign` (`id_career`),
  CONSTRAINT `media_files_id_career_foreign` FOREIGN KEY (`id_career`) REFERENCES `careers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `media_files_id_course_foreign` FOREIGN KEY (`id_course`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `media_files_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.media_files: ~4 rows (aproximadamente)
DELETE FROM `media_files`;
INSERT INTO `media_files` (`id`, `student_id`, `id_course`, `id_career`, `type`, `file`, `created_at`, `updated_at`) VALUES
	(3, NULL, 1, NULL, 'foto_de_curso', 'course_images/LUMEP4Z5GgsW7wm4zQaT68O1XfL0xJbiAKbZgImn.jpg', '2024-10-15 05:28:56', '2024-10-15 05:28:56'),
	(4, NULL, 2, NULL, 'foto_de_curso', 'course_images/3VdhKyuUw2iu3ZGCKR6Rz2Lt2D1E5svNHTqZPAIO.jpg', '2024-10-15 05:33:26', '2024-10-15 05:33:26'),
	(5, 1, NULL, NULL, 'documentos_estudiante', 'media_files/TlXSXsYQMGNcud75EHfLWXdcSXRouhlspOW9EOLe.pdf', '2024-10-15 05:40:53', '2024-10-15 05:40:53'),
	(6, 1, NULL, NULL, 'foto_tipo_carnet', 'media_files/yGdFsG9aog34LcS9Y2oMIkDqDvmNrh1eKTMevSZ8.jpg', '2024-10-15 05:40:53', '2024-10-15 05:40:53');

-- Volcando estructura para tabla sistema_web_v5.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.migrations: ~32 rows (aproximadamente)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
	(4, '2019_08_19_000000_create_failed_jobs_table', 1),
	(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(6, '2024_05_23_021348_create_sessions_table', 1),
	(7, '2024_06_04_211140_add_role_to_users_table', 1),
	(8, '2024_06_09_001703_add_disabled_to_users_table', 1),
	(9, '2024_07_03_015439_create_students_table', 1),
	(10, '2024_07_03_225620_update_matricula_default_value_in_students_table', 1),
	(11, '2024_07_03_230734_update_fields_in_students_table', 1),
	(12, '2024_07_04_174328_create_media_files_table', 1),
	(13, '2024_07_05_193415_add_moodle_columns_to_students_table', 1),
	(14, '2024_07_22_013805_create_courses_table', 1),
	(15, '2024_07_22_013826_add_moodleuser_to_users_table', 1),
	(16, '2024_07_22_190039_create_careers_table', 1),
	(17, '2024_07_22_190124_create_years_table', 1),
	(18, '2024_07_22_190142_update_courses_table', 1),
	(19, '2024_07_23_002013_add_cant_estudiantes_to_careers', 2),
	(20, '2024_07_23_002047_add_cant_estudiantes_to_years', 3),
	(21, '2024_07_23_011955_add_id_moodle_to_careers', 4),
	(22, '2024_07_23_012030_add_id_moodle_to_years', 5),
	(23, '2024_07_23_012112_add_id_moodle_to_courses', 6),
	(24, '2024_07_24_135536_modify_courses_table', 7),
	(25, '2024_07_25_011314_create_enrollments_table', 8),
	(26, '2024_07_27_222302_create_products_table', 9),
	(27, '2024_07_27_222314_create_payments_table', 10),
	(28, '2024_07_27_235256_create_debts_table', 11),
	(29, '2024_07_29_214735_rename_cantidad_to_monto_pagado_in_payments_table', 12),
	(30, '2024_07_30_005326_modify_columns_in_payments_and_products', 13),
	(31, '2024_10_15_012057_add_id_course_to_media_files_table', 14),
	(32, '2024_08_08_001245_add_id_career_to_media_files_table', 15);

-- Volcando estructura para tabla sistema_web_v5.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.password_reset_tokens: ~0 rows (aproximadamente)
DELETE FROM `password_reset_tokens`;

-- Volcando estructura para tabla sistema_web_v5.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_student` bigint(20) unsigned NOT NULL,
  `id_product` bigint(20) unsigned NOT NULL,
  `fecha` datetime NOT NULL,
  `monto_pagado` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_debt` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_id_student_foreign` (`id_student`),
  KEY `payments_id_product_foreign` (`id_product`),
  KEY `payments_id_debt_foreign` (`id_debt`),
  CONSTRAINT `payments_id_debt_foreign` FOREIGN KEY (`id_debt`) REFERENCES `debts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_id_product_foreign` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_id_student_foreign` FOREIGN KEY (`id_student`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.payments: ~2 rows (aproximadamente)
DELETE FROM `payments`;
INSERT INTO `payments` (`id`, `id_student`, `id_product`, `fecha`, `monto_pagado`, `created_at`, `updated_at`, `id_debt`) VALUES
	(1, 1, 1, '2024-10-15 01:37:47', 10.00, '2024-10-15 05:37:47', '2024-10-15 05:37:47', 1),
	(2, 1, 1, '2024-10-15 01:38:30', 5.00, '2024-10-15 05:38:30', '2024-10-15 05:38:30', 1);

-- Volcando estructura para tabla sistema_web_v5.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.personal_access_tokens: ~0 rows (aproximadamente)
DELETE FROM `personal_access_tokens`;

-- Volcando estructura para tabla sistema_web_v5.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.products: ~1 rows (aproximadamente)
DELETE FROM `products`;
INSERT INTO `products` (`id`, `nombre`, `descripcion`, `precio`, `created_at`, `updated_at`) VALUES
	(1, 'Carnet de estudiante', 'Carnet', 25.00, '2024-10-15 05:37:02', '2024-10-15 05:37:18');

-- Volcando estructura para tabla sistema_web_v5.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.sessions: ~1 rows (aproximadamente)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('1Y6JyJi1SPphWSAhZZJuIrxxEHNOkuOGFeHxfrSE', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieHkzb00zeXZWWDFVWXptSEJLRnVjZVFvWTBUSjRobUtHN3lnMnFIbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2VycyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxMzoibGFzdF9hY3Rpdml0eSI7aToxNzI4OTU2NTQyO30=', 1728956542);

-- Volcando estructura para tabla sistema_web_v5.students
CREATE TABLE IF NOT EXISTS `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `apellido_paterno` varchar(255) NOT NULL,
  `apellido_materno` varchar(255) NOT NULL,
  `num_carnet` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ciudad_domicilio` varchar(255) NOT NULL,
  `num_celular` varchar(255) NOT NULL,
  `moodle_user` varchar(255) DEFAULT NULL,
  `moodle_pass` varchar(255) DEFAULT NULL,
  `matricula` varchar(3) NOT NULL DEFAULT 'NO',
  `nombre_tutor` varchar(255) DEFAULT NULL,
  `celular_tutor` varchar(255) DEFAULT NULL,
  `ciudad_tutor` varchar(255) DEFAULT NULL,
  `parentesco` varchar(255) DEFAULT NULL,
  `disabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.students: ~1 rows (aproximadamente)
DELETE FROM `students`;
INSERT INTO `students` (`id`, `nombre`, `apellido_paterno`, `apellido_materno`, `num_carnet`, `email`, `ciudad_domicilio`, `num_celular`, `moodle_user`, `moodle_pass`, `matricula`, `nombre_tutor`, `celular_tutor`, `ciudad_tutor`, `parentesco`, `disabled`, `created_at`, `updated_at`) VALUES
	(1, 'Jorge Otto German', 'Guerrero', 'Menacho', '9756776', 'jorgeguerrero@gmail.com', 'Santa Cruz de la Sierra', '75073218', 'joguerrerom7618', '$2y$12$Oe63Z9bWrsGlVTP0pXHPy.EsIMUzU0ug4Pqfpe4kkDyUpo1Yu1SJq', 'SI', 'Luz Marina Camacho Maldonado', '75987676', 'Santa Cruz de la Sierra', 'Madre', 0, '2024-10-15 05:31:14', '2024-10-15 05:31:48');

-- Volcando estructura para tabla sistema_web_v5.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `moodleuser` varchar(255) DEFAULT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'Administrativo',
  `disabled` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.users: ~2 rows (aproximadamente)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `moodleuser`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`, `role`, `disabled`) VALUES
	(1, 'Andres Sebastian Guerrero Camacho', 'guerreroandres001@outlook.com', '2024-10-15 01:23:18', '$2y$12$/s3MbNgKvJjURFsk0k9byeN7EvxjmnG6GBpflHehIsyulxfxyQPNK', NULL, NULL, NULL, '2024-10-15 01:23:32', NULL, NULL, NULL, '2024-10-15 01:23:37', '2024-10-15 01:23:37', 'Superusuario', 0),
	(2, 'Neil Franco', 'neilfranco@gmail.com', NULL, '$2y$12$B05kzbYi.vC6Z1/Kk8CE6OIsfOnrsHRQK3FumXN6Erd1g96kpO6UC', 'docneilfranco', NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-15 05:25:33', '2024-10-15 05:25:33', 'Docente', 0);

-- Volcando estructura para tabla sistema_web_v5.years
CREATE TABLE IF NOT EXISTS `years` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_moodle` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext DEFAULT NULL,
  `cant_estudiantes` int(10) unsigned NOT NULL DEFAULT 0,
  `id_career` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `years_id_career_foreign` (`id_career`),
  CONSTRAINT `years_id_career_foreign` FOREIGN KEY (`id_career`) REFERENCES `careers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sistema_web_v5.years: ~2 rows (aproximadamente)
DELETE FROM `years`;
INSERT INTO `years` (`id`, `id_moodle`, `nombre`, `descripcion`, `cant_estudiantes`, `id_career`, `created_at`, `updated_at`) VALUES
	(1, 38, 'Primer año', 'Primer año de ENFERMERÍA.', 0, 1, '2024-10-15 05:27:27', '2024-10-15 05:39:10'),
	(2, 39, 'Segundo año', 'Descripcion', 1, 1, '2024-10-15 05:33:02', '2024-10-15 05:39:26');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
