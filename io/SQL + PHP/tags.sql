-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 12 2017 г., 23:11
-- Версия сервера: 5.5.47
-- Версия PHP: 5.5.27-1+deb.sury.org~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `tags`
--

-- --------------------------------------------------------

--
-- Структура таблицы `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `tag1` int(11) unsigned NOT NULL,
  `tag2` int(11) unsigned NOT NULL,
  UNIQUE KEY `tag1` (`tag1`,`tag2`),
  UNIQUE KEY `tag1_2` (`tag1`,`tag2`),
  KEY `tag2` (`tag2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `links`
--

INSERT INTO `links` (`tag1`, `tag2`) VALUES
(1, 2),
(2, 3),
(2, 4),
(2, 5),
(4, 6),
(4, 7),
(2, 8),
(4, 8),
(7, 8),
(2, 9),
(4, 9),
(7, 9),
(2, 10),
(3, 10),
(2, 11),
(3, 11),
(2, 12),
(3, 12),
(14, 15),
(22, 23),
(12, 24),
(22, 24),
(22, 25),
(24, 25),
(1, 26),
(24, 26),
(26, 31),
(39, 41),
(40, 41),
(50, 53),
(2, 81),
(3, 81),
(2, 82),
(3, 82),
(2, 83),
(3, 83),
(2, 85),
(4, 85),
(9, 85),
(2, 86),
(39, 87),
(40, 87),
(88, 89);

-- --------------------------------------------------------

--
-- Структура таблицы `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(254) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

--
-- Дамп данных таблицы `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(39, '1'),
(90, '123'),
(40, '2'),
(24, '2016'),
(41, '3'),
(87, '3 4'),
(55, '999'),
(12, 'akelpad'),
(48, 'asdasd'),
(81, 'atom'),
(23, 'cc'),
(9, 'coreldraw'),
(45, 'edit'),
(2, 'editor'),
(25, 'editors'),
(10, 'editplus'),
(5, 'gimp'),
(4, 'graphics'),
(36, 'hello'),
(18, 'hi'),
(8, 'illustrator'),
(82, 'jetbrains'),
(91, 'my'),
(11, 'notepad++'),
(31, 'os'),
(1, 'photoshop'),
(22, 'photoshops'),
(57, 'picasa'),
(6, 'raster'),
(83, 'sdgsfhfghgfhg'),
(15, 'SLAVA'),
(14, 'test'),
(13, 'test1'),
(3, 'text'),
(86, 'text2'),
(32, 'try'),
(29, 'ubuntu'),
(7, 'vector'),
(26, 'windows'),
(85, 'XYZ'),
(50, 'Ð´Ð²Ð¸Ð³Ð°Ñ‚ÐµÐ»ÑŒ'),
(53, 'Ð´Ð²Ð¸Ð³Ð°Ñ‚ÐµÐ»ÑŒ2'),
(88, 'ÐºÐ°Ñ€ÑˆÐµÑ€Ð¸Ð½Ð³'),
(89, 'Ð”ÐµÐ»Ð¸Ð¼Ð¾Ð±Ð¸Ð»ÑŒ');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `links_ibfk_3` FOREIGN KEY (`tag1`) REFERENCES `tags` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `links_ibfk_4` FOREIGN KEY (`tag2`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
