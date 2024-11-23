-- Volcando estructura de base de datos para users_service
CREATE DATABASE IF NOT EXISTS `users_service`;
USE `users_service`;

-- Volcando estructura para tabla users_service.estados
CREATE TABLE IF NOT EXISTS `estados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_status` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla users_service.estados: ~3 rows (aproximadamente)
INSERT INTO `estados` (`id`, `name_status`) VALUES
	(1, 'Activo'),
	(2, 'Inactivo'),
	(3, 'Bloqueado');

-- Volcando estructura para tabla users_service.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_rol` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla users_service.roles: ~3 rows (aproximadamente)
INSERT INTO `roles` (`id`, `name_rol`) VALUES
	(1, 'Admin'),
	(2, 'Empleado'),
	(3, 'Cliente');

-- Volcando estructura para tabla users_service.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `nombre_doc` varchar(250) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `telephone` varchar(250) DEFAULT NULL,
  `email` varchar(250) NOT NULL,
  `created_ad` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando estructura para tabla users_service.login
CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick_name` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `token` varchar(250) DEFAULT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `status` int(11),
  PRIMARY KEY (`id`),
  KEY `fk_login_usuario` (`usuario_id`),
  KEY `fk_login_estado` (`estado_id`),
  KEY `fk_login_rol` (`rol_id`),
  CONSTRAINT `fk_login_estado` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_login_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_login_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
