-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 192.168.0.103:3306
-- Время создания: Май 27 2021 г., 00:58
-- Версия сервера: 10.3.13-MariaDB
-- Версия PHP: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mikroadmin`
--
CREATE DATABASE IF NOT EXISTS `mikroadmin` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mikroadmin`;

-- --------------------------------------------------------

--
-- Структура таблицы `device_backup`
--

CREATE TABLE `device_backup` (
  `id_device` int(30) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `time` time NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `device_user`
--

CREATE TABLE `device_user` (
  `id` int(10) NOT NULL,
  `device_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user` varbinary(50) NOT NULL,
  `password` varbinary(50) NOT NULL,
  `ip_address` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `id_global_user` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `global_user`
--

CREATE TABLE `global_user` (
  `id` int(10) NOT NULL,
  `name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `global_user`
--

INSERT INTO `global_user` (`id`, `name`, `password`, `mail`) VALUES
(1, 'Админ328', '827ccb0eea8a706c4c34a16891f84e7b', 'binikup@gmail.com'),
(2, 'Sergey05', '54321', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `device_backup`
--
ALTER TABLE `device_backup`
  ADD KEY `id_device` (`id_device`);

--
-- Индексы таблицы `device_user`
--
ALTER TABLE `device_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_global_user` (`id_global_user`);

--
-- Индексы таблицы `global_user`
--
ALTER TABLE `global_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `device_user`
--
ALTER TABLE `device_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `global_user`
--
ALTER TABLE `global_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `device_backup`
--
ALTER TABLE `device_backup`
  ADD CONSTRAINT `device_backup_ibfk_1` FOREIGN KEY (`id_device`) REFERENCES `device_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `device_user`
--
ALTER TABLE `device_user`
  ADD CONSTRAINT `device_user_ibfk_1` FOREIGN KEY (`id_global_user`) REFERENCES `global_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
