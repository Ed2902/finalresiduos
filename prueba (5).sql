-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-06-2024 a las 22:23:55
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
-- Base de datos: `prueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ingreso`
--

CREATE TABLE `detalle_ingreso` (
  `id` int(11) NOT NULL,
  `ingreso_id` int(11) NOT NULL,
  `id_inventarioFK` int(11) NOT NULL,
  `cantidad` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_ingreso`
--

INSERT INTO `detalle_ingreso` (`id`, `ingreso_id`, `id_inventarioFK`, `cantidad`) VALUES
(1, 3, 0, 1),
(2, 4, 2, 1),
(3, 5, 3, 1),
(4, 6, 4, 1),
(5, 7, 5, 1),
(6, 8, 6, 32),
(7, 9, 7, 350);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso`
--

CREATE TABLE `ingreso` (
  `id` int(11) NOT NULL,
  `suma_total_kilos` double NOT NULL,
  `fecha_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingreso`
--

INSERT INTO `ingreso` (`id`, `suma_total_kilos`, `fecha_hora`) VALUES
(1, 2, '2024-06-04 19:45:19'),
(2, 1, '2024-06-04 19:46:25'),
(3, 1, '2024-06-04 19:47:10'),
(4, 1, '2024-06-04 19:48:02'),
(5, 1, '2024-06-04 19:48:51'),
(6, 1, '2024-06-04 19:52:59'),
(7, 1, '2024-06-04 20:43:16'),
(8, 32, '2024-06-04 20:47:53'),
(9, 350, '2024-06-04 22:09:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id_inventario` int(11) NOT NULL,
  `id_productoFK` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `peso` double NOT NULL,
  `id_proveedor` varchar(50) NOT NULL,
  `valorPorKilo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_inventario`, `id_productoFK`, `id_usuario`, `peso`, `id_proveedor`, `valorPorKilo`) VALUES
(1, 2, 1, 1, '1', 1),
(2, 1, 1, 1, '1', 1),
(3, 2, 1, 1, '1', 1),
(4, 2, 1, 1, '1', 1),
(5, 2, 1, 1, '1', 1),
(6, 2, 1, 32, '1', 32),
(7, 2, 1, 350, '1', 65000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `referencia` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `fecha` datetime NOT NULL,
  `id_usuarioFK` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `referencia`, `tipo`, `fecha`, `id_usuarioFK`) VALUES
(1, '1', '1', '1', '2024-06-04 12:44:02', '1'),
(2, '2', '2', '2', '2024-06-04 12:44:51', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `representantelegal` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `telefono` int(11) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `camara_comercio` varchar(50) NOT NULL,
  `rut` varchar(50) NOT NULL,
  `cc_representante` varchar(50) NOT NULL,
  `certificacion_comercial` varchar(50) NOT NULL,
  `certificacion_bancaria` varchar(50) NOT NULL,
  `circular_170` varchar(50) NOT NULL,
  `acuerdos_seguridad` varchar(50) NOT NULL,
  `estados_financieros` varchar(50) NOT NULL,
  `autorizacion_tratamiento_datos` varchar(50) NOT NULL,
  `visita` varchar(50) NOT NULL,
  `antecedentes_judiciales` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `nombre`, `representantelegal`, `correo`, `telefono`, `direccion`, `fecha_registro`, `camara_comercio`, `rut`, `cc_representante`, `certificacion_comercial`, `certificacion_bancaria`, `circular_170`, `acuerdos_seguridad`, `estados_financieros`, `autorizacion_tratamiento_datos`, `visita`, `antecedentes_judiciales`) VALUES
('1', '1', '1', '1', 1, '1', '2024-06-04 00:00:00', './guardar/1/Orden-296759.pdf', './guardar/1/Orden-296759.pdf', './guardar/1/Orden-296759.pdf', './guardar/1/Orden-296759.pdf', './guardar/1/Orden-296759.pdf', './guardar/1/Orden-296759.pdf', './guardar/1/Orden-296759.pdf', './guardar/1/Orden-296759.pdf', './guardar/1/Orden-296759.pdf', './guardar/1/Orden-296759.pdf', './guardar/1/Orden-296759.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `permiso` int(11) NOT NULL,
  `contraseña` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `cargo`, `permiso`, `contraseña`) VALUES
(1, '1', '1', 1, '1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingreso_id` (`ingreso_id`),
  ADD KEY `id_inventarioFK` (`id_inventarioFK`);

--
-- Indices de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id_inventario`),
  ADD KEY `id_productoFK` (`id_productoFK`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  ADD CONSTRAINT `detalle_ingreso_ibfk_1` FOREIGN KEY (`ingreso_id`) REFERENCES `ingreso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_ingreso_ibfk_2` FOREIGN KEY (`id_inventarioFK`) REFERENCES `inventario` (`id_inventario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`id_productoFK`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventario_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
