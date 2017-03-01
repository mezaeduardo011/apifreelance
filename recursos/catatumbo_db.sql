-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 24-03-2016 a las 23:31:05
-- Versión del servidor: 5.5.41
-- Versión de PHP: 5.4.44-0+deb7u1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `catatumbo_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `outh`
--

CREATE TABLE IF NOT EXISTS `outh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tocken` varchar(200) NOT NULL,
  `secret` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `redirect_url` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Entidad encargada de administrar los clientes que se conecte' AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `outh`
--

INSERT INTO `outh` (`id`, `tocken`, `secret`, `name`, `redirect_url`, `created_at`) VALUES
(1, '6dcf4c66f2ca79b6634f25bc046fa096', 'f065e869d5fb6e21216701d6e45e46a7', 'cliente Rest', 'mobbiclick.com', '2015-11-03 03:21:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Entidad encargada de registrar los usuarios del sistema' AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `clave`, `created_at`) VALUES
(2, 'GREGORIO BOLÍVAR', 'gbolivar', '123456', '2016-03-21 04:03:55');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
