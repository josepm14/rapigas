-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 31-08-2025 a las 15:37:49
-- Versión del servidor: 5.7.31
-- Versión de PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion_usuario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(2) DEFAULT NULL,
  `nombre` varchar(8) DEFAULT NULL,
  `apellido` varchar(9) DEFAULT NULL,
  `dni` int(8) DEFAULT NULL,
  `direccion` varchar(29) DEFAULT NULL,
  `telefono` int(9) DEFAULT NULL,
  `cargo` varchar(10) DEFAULT NULL,
  `responsabilidad` varchar(15) DEFAULT NULL,
  `usuario` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `cargo`, `responsabilidad`, `usuario`) VALUES
(1, 'Diana', 'Ramirez', 84640739, 'Calle Aguilar 591, Ciudad 4', 946706048, 'repartidor', 'delivery', 'DRamirez'),
(2, 'Marta', 'Castro', 50476607, 'Calle Morales 239, Ciudad 5', 954506700, 'repartidor', 'delivery', 'MCastro'),
(3, 'Juan', 'Ortega', 94904502, 'Calle Marquez 450, Ciudad 1', 931457893, 'repartidor', 'delivery', 'JOrtega'),
(4, 'Lucía', 'Morales', 85453366, 'Calle Salazar 65, Ciudad 4', 957463211, 'Jefe Zonal', 'Supervisor', 'LMorales'),
(5, 'Pedro', 'Hernández', 46235893, 'Calle Ramirez 665, Ciudad 3', 947002348, 'Vendedor', 'Ventas', 'PHernandez'),
(6, 'Patricia', 'Flores', 94602578, 'Calle Torres 853, Ciudad 6', 942507617, 'Vendedor', 'Ventas', 'PFlores'),
(7, 'Héctor', 'Aguilar', 41840269, 'Calle Vargas 589, Ciudad 2', 945207617, 'contador', 'contabilidad', 'HAguilar'),
(8, 'Carlos', 'Ruiz', 90745934, 'Calle Salazar 714, Ciudad 2', 954236000, 'tecnico', 'Soporte tecnico', 'CRuiz'),
(9, 'Gladis', 'Torres', 44210636, 'Calle Rojas 733, Ciudad 3', 942567891, 'Vendedor', 'Ventas', 'GTorres'),
(10, 'Luis', 'Rojas', 59143252, 'Calle Marquez 134, Ciudad 5', 963457231, 'Vendedor', 'Ventas', 'LRojas'),
(11, 'Rosa', 'Vargas', 41505349, 'Calle Fernandez 502, Ciudad 4', 953421678, 'tecnico', 'Soporte tecnico', 'RVargas'),
(12, 'Juan', 'Ramirez', 61430563, 'Calle Rojas 225, Ciudad 2', 946231789, 'tecnico', 'Soporte tecnico', 'JRamirez'),
(13, 'Diana', 'Soto', 94653400, 'Calle Fernandez 302, Ciudad 6', 942130987, 'Vendedor', 'Ventas', 'DSoto'),
(14, 'Marta', 'Hernández', 41532209, 'Calle Vargas 650, Ciudad 3', 951987654, 'tecnico', 'Soporte tecnico', 'MHernandez'),
(15, 'Pedro', 'Aguilar', 96256003, 'Calle Castro 322, Ciudad 4', 947886553, 'Jefe Zonal', 'supervisor', 'PAguilar');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
