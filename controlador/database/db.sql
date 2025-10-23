-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-10-2025 a las 14:29:06
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_vuelos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_aerolinea`
--

CREATE TABLE `tb_aerolinea` (
  `id_aerolinea` int(11) NOT NULL,
  `nombre_aerolinea` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_aerolinea`
--

INSERT INTO `tb_aerolinea` (`id_aerolinea`, `nombre_aerolinea`) VALUES
(2, 'Latam'),
(3, 'Viva Air'),
(4, 'satena');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_asiento_vuelo`
--

CREATE TABLE `tb_asiento_vuelo` (
  `id_asiento` int(11) NOT NULL,
  `vuelo_id` int(11) NOT NULL,
  `fila` int(11) NOT NULL,
  `letra` char(1) NOT NULL,
  `codigo_asiento` varchar(10) NOT NULL,
  `clase` varchar(20) DEFAULT 'Económica',
  `estado` enum('Disponible','Pagado') DEFAULT 'Disponible',
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_reserva` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_asiento_vuelo`
--

INSERT INTO `tb_asiento_vuelo` (`id_asiento`, `vuelo_id`, `fila`, `letra`, `codigo_asiento`, `clase`, `estado`, `usuario_id`, `fecha_reserva`) VALUES
(25, 2, 1, 'A', '1A', 'Económica', 'Disponible', NULL, NULL),
(26, 2, 1, 'B', '1B', 'Económica', 'Disponible', NULL, NULL),
(27, 2, 1, 'C', '1C', 'Económica', 'Disponible', NULL, NULL),
(28, 2, 1, 'D', '1D', 'Económica', 'Disponible', NULL, NULL),
(29, 2, 2, 'A', '2A', 'Económica', 'Disponible', NULL, NULL),
(30, 2, 2, 'B', '2B', 'Económica', 'Pagado', 2, '2025-10-22 05:19:34'),
(31, 2, 2, 'C', '2C', 'Económica', 'Pagado', 2, '2025-10-22 05:19:34'),
(32, 2, 2, 'D', '2D', 'Económica', 'Disponible', NULL, NULL),
(33, 2, 3, 'A', '3A', 'Económica', 'Disponible', NULL, NULL),
(34, 2, 3, 'B', '3B', 'Económica', 'Pagado', 2, '2025-10-22 05:19:34'),
(35, 2, 3, 'C', '3C', 'Económica', 'Pagado', 2, '2025-10-22 05:19:34'),
(36, 2, 3, 'D', '3D', 'Económica', 'Disponible', NULL, NULL),
(37, 2, 4, 'A', '4A', 'Económica', 'Disponible', NULL, NULL),
(38, 2, 4, 'B', '4B', 'Económica', 'Disponible', NULL, NULL),
(39, 2, 4, 'C', '4C', 'Económica', 'Disponible', NULL, NULL),
(40, 2, 4, 'D', '4D', 'Económica', 'Disponible', NULL, NULL),
(41, 2, 5, 'A', '5A', 'Económica', 'Disponible', NULL, NULL),
(42, 2, 5, 'B', '5B', 'Económica', 'Disponible', NULL, NULL),
(43, 2, 5, 'C', '5C', 'Económica', 'Disponible', NULL, NULL),
(44, 2, 5, 'D', '5D', 'Económica', 'Disponible', NULL, NULL),
(45, 2, 6, 'A', '6A', 'Económica', 'Disponible', NULL, NULL),
(46, 2, 6, 'B', '6B', 'Económica', 'Disponible', NULL, NULL),
(47, 2, 6, 'C', '6C', 'Económica', 'Disponible', NULL, NULL),
(48, 2, 6, 'D', '6D', 'Económica', 'Disponible', NULL, NULL),
(49, 2, 7, 'A', '7A', 'Económica', 'Disponible', NULL, NULL),
(50, 2, 7, 'B', '7B', 'Económica', 'Disponible', NULL, NULL),
(51, 2, 7, 'C', '7C', 'Económica', 'Disponible', NULL, NULL),
(52, 2, 7, 'D', '7D', 'Económica', 'Disponible', NULL, NULL),
(53, 2, 8, 'A', '8A', 'Económica', 'Disponible', NULL, NULL),
(54, 2, 8, 'B', '8B', 'Económica', 'Disponible', NULL, NULL),
(55, 2, 8, 'C', '8C', 'Económica', 'Disponible', NULL, NULL),
(56, 2, 8, 'D', '8D', 'Económica', 'Disponible', NULL, NULL),
(57, 3, 1, 'A', '1A', 'Económica', 'Disponible', NULL, NULL),
(58, 3, 1, 'B', '1B', 'Económica', 'Disponible', NULL, NULL),
(59, 3, 1, 'C', '1C', 'Económica', 'Disponible', NULL, NULL),
(60, 3, 1, 'D', '1D', 'Económica', 'Disponible', NULL, NULL),
(61, 3, 2, 'A', '2A', 'Económica', 'Disponible', NULL, NULL),
(62, 3, 2, 'B', '2B', 'Económica', 'Pagado', 2, '2025-10-22 04:37:56'),
(63, 3, 2, 'C', '2C', 'Económica', 'Pagado', 2, '2025-10-22 04:37:56'),
(64, 3, 2, 'D', '2D', 'Económica', 'Disponible', NULL, NULL),
(65, 3, 3, 'A', '3A', 'Económica', 'Disponible', NULL, NULL),
(66, 3, 3, 'B', '3B', 'Económica', 'Pagado', 2, '2025-10-22 04:37:56'),
(67, 3, 3, 'C', '3C', 'Económica', 'Disponible', NULL, NULL),
(68, 3, 3, 'D', '3D', 'Económica', 'Disponible', NULL, NULL),
(69, 3, 4, 'A', '4A', 'Económica', 'Disponible', NULL, NULL),
(70, 3, 4, 'B', '4B', 'Económica', 'Pagado', 2, '2025-10-22 04:38:14'),
(71, 3, 4, 'C', '4C', 'Económica', 'Pagado', 2, '2025-10-22 04:38:14'),
(72, 3, 4, 'D', '4D', 'Económica', 'Disponible', NULL, NULL),
(73, 3, 5, 'A', '5A', 'Económica', 'Disponible', NULL, NULL),
(74, 3, 5, 'B', '5B', 'Económica', 'Disponible', NULL, NULL),
(75, 3, 5, 'C', '5C', 'Económica', 'Disponible', NULL, NULL),
(76, 3, 5, 'D', '5D', 'Económica', 'Disponible', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_avion`
--

CREATE TABLE `tb_avion` (
  `id_avion` int(11) NOT NULL,
  `codigo_avion` varchar(50) NOT NULL,
  `modelo_id` int(11) NOT NULL,
  `aerolinea_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_avion`
--

INSERT INTO `tb_avion` (`id_avion`, `codigo_avion`, `modelo_id`, `aerolinea_id`) VALUES
(2, 'LT-101', 2, 2),
(3, 'VV-301', 3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_ciudad`
--

CREATE TABLE `tb_ciudad` (
  `id_ciudad` int(11) NOT NULL,
  `nombre_ciudad` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_ciudad`
--

INSERT INTO `tb_ciudad` (`id_ciudad`, `nombre_ciudad`) VALUES
(1, 'Bogotá'),
(2, 'Medellín'),
(3, 'Cali'),
(4, 'Cartagena'),
(5, 'Barranquilla');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_control_compra`
--

CREATE TABLE `tb_control_compra` (
  `id_compra` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `vuelo_id` int(11) NOT NULL,
  `fecha_compra` datetime DEFAULT current_timestamp(),
  `estado` enum('Pagado','Pendiente','Cancelado') NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_modelo_avion`
--

CREATE TABLE `tb_modelo_avion` (
  `id_modelo_avion` int(11) NOT NULL,
  `nombre_modelo_avion` varchar(100) NOT NULL,
  `filas_modelo_avion` int(11) NOT NULL DEFAULT 6,
  `columnas_modelo_avion` int(11) NOT NULL DEFAULT 4
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_modelo_avion`
--

INSERT INTO `tb_modelo_avion` (`id_modelo_avion`, `nombre_modelo_avion`, `filas_modelo_avion`, `columnas_modelo_avion`) VALUES
(1, 'Boeing 737', 6, 4),
(2, 'Airbus A320', 8, 4),
(3, 'Embraer 190', 5, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `correo_usuario` varchar(100) NOT NULL,
  `usuario_login` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_ultimo_login` datetime DEFAULT NULL,
  `bloqueado_hasta` datetime DEFAULT NULL,
  `apellido1` varchar(30) NOT NULL,
  `apellido2` varchar(30) NOT NULL,
  `genero` enum('Hombre','Mujer') NOT NULL,
  `numero_documento` varchar(50) NOT NULL,
  `rol` enum('Usuario','Administrador','ADS') DEFAULT 'Usuario',
  `tipo_documeto` enum('Cedula','Targeta de idenda','Pasaporte','Registro Civil') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_usuario`
--

INSERT INTO `tb_usuario` (`id_usuario`, `nombre_usuario`, `correo_usuario`, `usuario_login`, `password_hash`, `fecha_creacion`, `fecha_ultimo_login`, `bloqueado_hasta`, `apellido1`, `apellido2`, `genero`, `numero_documento`, `rol`, `tipo_documeto`) VALUES
(1, 'Administrador', 'admin@sistema_vuelos.com', 'admin', '8cb2237d0679ca88db6464eac60da96345513964', '2025-10-22 04:06:16', NULL, NULL, '', '', 'Hombre', '', 'Usuario', 'Cedula'),
(2, 'mena', 'mena@gmail.com', 'mena', '$2y$10$furhZwU95Pf0N0zEE.r4O.joDWgzqYedsBdnPSWZaojJUKRGvBzDC', '2025-10-22 04:11:15', '2025-10-23 07:27:36', NULL, '', '', 'Hombre', '', 'Usuario', 'Cedula'),
(3, 'menaddd', 'david@gmail.com', 'd', '$2y$10$rYC6d6VFegxJbZX7V.znyusO/f5ldXUSCEsIKgyV2PHdOZdi0AM8u', '2025-10-23 00:55:52', '2025-10-23 01:11:25', NULL, 'd', 'dd', 'Hombre', '132323', 'Administrador', 'Cedula'),
(4, 'admi', 'menamorecdf@gmail.com', 'ADS', '$2y$10$FLzzuQ1XeZLZhZPODqUP.uVT3T4aCcwG0ZIgwtmJSoH.DZBVjZp0m', '2025-10-23 02:02:26', '2025-10-23 02:03:29', NULL, 'admi', 'Cristian', 'Hombre', '12334', 'ADS', 'Cedula');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_vuelo`
--

CREATE TABLE `tb_vuelo` (
  `id_vuelo` int(11) NOT NULL,
  `codigo_vuelo` varchar(50) NOT NULL,
  `avion_id` int(11) NOT NULL,
  `ciudad_origen_id` int(11) NOT NULL,
  `ciudad_destino_id` int(11) NOT NULL,
  `fecha_salida` date NOT NULL,
  `hora_salida` time NOT NULL,
  `precio_base` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_vuelo`
--

INSERT INTO `tb_vuelo` (`id_vuelo`, `codigo_vuelo`, `avion_id`, `ciudad_origen_id`, `ciudad_destino_id`, `fecha_salida`, `hora_salida`, `precio_base`) VALUES
(2, 'FL002', 2, 2, 3, '2025-10-24', '15:30:00', 280000.00),
(3, 'FL003', 3, 4, 5, '2025-11-03', '07:45:00', 310000.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tb_aerolinea`
--
ALTER TABLE `tb_aerolinea`
  ADD PRIMARY KEY (`id_aerolinea`);

--
-- Indices de la tabla `tb_asiento_vuelo`
--
ALTER TABLE `tb_asiento_vuelo`
  ADD PRIMARY KEY (`id_asiento`),
  ADD KEY `vuelo_id` (`vuelo_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `tb_avion`
--
ALTER TABLE `tb_avion`
  ADD PRIMARY KEY (`id_avion`),
  ADD KEY `modelo_id` (`modelo_id`),
  ADD KEY `aerolinea_id` (`aerolinea_id`);

--
-- Indices de la tabla `tb_ciudad`
--
ALTER TABLE `tb_ciudad`
  ADD PRIMARY KEY (`id_ciudad`);

--
-- Indices de la tabla `tb_control_compra`
--
ALTER TABLE `tb_control_compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `vuelo_id` (`vuelo_id`);

--
-- Indices de la tabla `tb_modelo_avion`
--
ALTER TABLE `tb_modelo_avion`
  ADD PRIMARY KEY (`id_modelo_avion`);

--
-- Indices de la tabla `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo_usuario` (`correo_usuario`),
  ADD UNIQUE KEY `usuario_login` (`usuario_login`);

--
-- Indices de la tabla `tb_vuelo`
--
ALTER TABLE `tb_vuelo`
  ADD PRIMARY KEY (`id_vuelo`),
  ADD KEY `avion_id` (`avion_id`),
  ADD KEY `ciudad_origen_id` (`ciudad_origen_id`),
  ADD KEY `ciudad_destino_id` (`ciudad_destino_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tb_aerolinea`
--
ALTER TABLE `tb_aerolinea`
  MODIFY `id_aerolinea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tb_asiento_vuelo`
--
ALTER TABLE `tb_asiento_vuelo`
  MODIFY `id_asiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `tb_avion`
--
ALTER TABLE `tb_avion`
  MODIFY `id_avion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tb_ciudad`
--
ALTER TABLE `tb_ciudad`
  MODIFY `id_ciudad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tb_control_compra`
--
ALTER TABLE `tb_control_compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_modelo_avion`
--
ALTER TABLE `tb_modelo_avion`
  MODIFY `id_modelo_avion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tb_vuelo`
--
ALTER TABLE `tb_vuelo`
  MODIFY `id_vuelo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_asiento_vuelo`
--
ALTER TABLE `tb_asiento_vuelo`
  ADD CONSTRAINT `tb_asiento_vuelo_ibfk_1` FOREIGN KEY (`vuelo_id`) REFERENCES `tb_vuelo` (`id_vuelo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_asiento_vuelo_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuario` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_avion`
--
ALTER TABLE `tb_avion`
  ADD CONSTRAINT `tb_avion_ibfk_1` FOREIGN KEY (`modelo_id`) REFERENCES `tb_modelo_avion` (`id_modelo_avion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_avion_ibfk_2` FOREIGN KEY (`aerolinea_id`) REFERENCES `tb_aerolinea` (`id_aerolinea`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_control_compra`
--
ALTER TABLE `tb_control_compra`
  ADD CONSTRAINT `tb_control_compra_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_control_compra_ibfk_2` FOREIGN KEY (`vuelo_id`) REFERENCES `tb_vuelo` (`id_vuelo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_vuelo`
--
ALTER TABLE `tb_vuelo`
  ADD CONSTRAINT `tb_vuelo_ibfk_1` FOREIGN KEY (`avion_id`) REFERENCES `tb_avion` (`id_avion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_vuelo_ibfk_2` FOREIGN KEY (`ciudad_origen_id`) REFERENCES `tb_ciudad` (`id_ciudad`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_vuelo_ibfk_3` FOREIGN KEY (`ciudad_destino_id`) REFERENCES `tb_ciudad` (`id_ciudad`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
