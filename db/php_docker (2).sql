-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: db:3306
-- Üretim Zamanı: 14 Ara 2023, 11:47:04
-- Sunucu sürümü: 8.2.0
-- PHP Sürümü: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `php_docker`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `game`
--

CREATE TABLE `game` (
  `user_id` int DEFAULT NULL,
  `highest_score` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `game`
--

INSERT INTO `game` (`user_id`, `highest_score`) VALUES
(3, 2),
(4, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user2`
--

CREATE TABLE `user2` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `user2`
--

INSERT INTO `user2` (`id`, `name`, `email`, `password_hash`) VALUES
(3, 'sueda', 'sueda@mail.com', '$2y$10$mxBirARSNAS9TeUUWYIMce1ni6npJwAz9pS0.KRnXA86g4hLYnf72'),
(4, 'deneme', 'deneme@mail.com', '$2y$10$oAtgGpu0K2mR0JogeHER0OaVIpB68dr.TDnU9hB956gMbq.T2hJP2');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `game`
--
ALTER TABLE `game`
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `user2`
--
ALTER TABLE `user2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `user2`
--
ALTER TABLE `user2`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `game_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user2` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
