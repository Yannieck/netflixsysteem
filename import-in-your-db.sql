-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 20 dec 2021 om 14:32
-- Serverversie: 10.4.20-MariaDB
-- PHP-versie: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `null pointer videos`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `account`
--

CREATE TABLE `account` (
  `Id` int(11) NOT NULL,
  `MembershipName` varchar(6) NOT NULL,
  `Name` varchar(80) DEFAULT 'Unknown',
  `Username` varchar(40) NOT NULL,
  `Email` varchar(300) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Biography` varchar(100) DEFAULT NULL,
  `Photo` varchar(150) DEFAULT NULL,
  `GithubLink` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `account`
--

INSERT INTO `account` (`Id`, `MembershipName`, `Name`, `Username`, `Email`, `Password`, `Biography`, `Photo`, `GithubLink`) VALUES
(1, 'Admin', 'Admin', 'Developers', 'developer@gmail.com', '$2y$10$tc1ctq28T5bm9DpO42rvzeI0PFGecEhUr1l1Gb.zcWezqj5VA06/W', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bookmark`
--

CREATE TABLE `bookmark` (
  `AccountId` int(11) NOT NULL,
  `QuestionTitle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `comment`
--

CREATE TABLE `comment` (
  `Id` int(11) NOT NULL,
  `VideoId` int(11) NOT NULL,
  `AccountId` int(11) NOT NULL,
  `Content` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `like`
--

CREATE TABLE `like` (
  `Id` int(11) NOT NULL,
  `AccountId` int(11) NOT NULL,
  `CommentId` int(11) DEFAULT NULL,
  `VideoId` int(11) DEFAULT NULL,
  `Type` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `membership`
--

CREATE TABLE `membership` (
  `Name` varchar(6) NOT NULL,
  `Price` double(2,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `membership`
--

INSERT INTO `membership` (`Name`, `Price`) VALUES
('Admin', 0.00),
('Junior', 0.99),
('Prof', 0.00),
('Senior', 0.99);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `question`
--

CREATE TABLE `question` (
  `Title` varchar(255) NOT NULL,
  `AccountId` int(11) NOT NULL,
  `Content` varchar(8000) NOT NULL,
  `AskDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `question`
--

INSERT INTO `question` (`Title`, `AccountId`, `Content`, `AskDate`) VALUES
('How to change parser titles when using Argparse without modifying internal variables?', 1, '\r\n\r\nI\'m using Python\'s argparse module to create a CLI for my application. I\'ve made a subparsers variable to store the parsers for each command, but when I can\'t find a way to change the title of the subparsers without modifying parser\'s (the main ArgumentParser\'s) internal variables.', '2021-12-14 12:19:20'),
('What are some ways to avoid String.substring from returning substring with invalid unicode character', 1, '\r\n\r\nRecently, only I notice that, it is possible for substring to return string with invalid unicode character.\r\n\r\nFor instance\r\n\r\npublic class Main {\r\n\r\n    public static void main(String[] args) {\r\n        String text = \'🥦_Salade verte\';\r\n\r\n        /* We should avoid using endIndex = 1, as it will cause an invalid character in the returned substring. */\r\n        // 1 : ?\r\n        System.out.println(\'1 : \' + text.substring(0, 1));\r\n\r\n        // 2 : 🥦\r\n        System.out.println(\'2 : \' + text.substring(0, 2));\r\n\r\n        // 3 : 🥦_\r\n        System.out.println(\'3 : \' + text.substring(0, 3));\r\n\r\n        // 4 : 🥦_S\r\n        System.out.println(\'4 : \' + text.substring(0, 4));\r\n    }\r\n}\r\n\r\nI was wondering, when trimming a long string with String.substring, what are some good ways to avoid the returned substring from containing invalid unicode?\r\n', '2021-04-04 16:57:19'),
('Why can\'t a const mutable lambda with an auto& parameter be invoked?', 1, '\r\n\r\n#include <type_traits>\r\n\r\nint main()\r\n{\r\n    auto f1 = [](auto&) mutable {};\r\n    static_assert(std::is_invocable_v<decltype(f1), int&>); // ok\r\n\r\n    auto const f2 = [](auto&) {};\r\n    static_assert(std::is_invocable_v<decltype(f2), int&>); // ok\r\n\r\n    auto const f3 = [](auto&) mutable {};\r\n    static_assert(std::is_invocable_v<decltype(f3), int&>); // failed\r\n}\r\n\r\nSee demo\r\n\r\nWhy can\'t a const mutable lambda take a reference argument?\r\n', '2021-12-11 12:19:30');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `subtag`
--

CREATE TABLE `subtag` (
  `Id` int(11) NOT NULL,
  `TagId` int(11) NOT NULL,
  `SubCategory` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tag`
--

CREATE TABLE `tag` (
  `Id` int(11) NOT NULL,
  `Category` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tag_question`
--

CREATE TABLE `tag_question` (
  `SubTagId` int(11) NOT NULL,
  `QuestionTitle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `video`
--

CREATE TABLE `video` (
  `Id` int(11) NOT NULL,
  `QuestionTitle` varchar(100) NOT NULL,
  `AccountId` int(11) NOT NULL,
  `Description` varchar(8000) DEFAULT NULL,
  `UploadDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `File` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `GithubLink` (`GithubLink`),
  ADD KEY `MembershipName` (`MembershipName`);

--
-- Indexen voor tabel `bookmark`
--
ALTER TABLE `bookmark`
  ADD PRIMARY KEY (`AccountId`,`QuestionTitle`),
  ADD KEY `QuestionTitle` (`QuestionTitle`);

--
-- Indexen voor tabel `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `VideoId` (`VideoId`),
  ADD KEY `AccountId` (`AccountId`);

--
-- Indexen voor tabel `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `AccountId` (`AccountId`),
  ADD KEY `CommentId` (`CommentId`),
  ADD KEY `VideoId` (`VideoId`);

--
-- Indexen voor tabel `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`Name`);

--
-- Indexen voor tabel `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`Title`),
  ADD KEY `AccountId` (`AccountId`);

--
-- Indexen voor tabel `subtag`
--
ALTER TABLE `subtag`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `TagId` (`TagId`);

--
-- Indexen voor tabel `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`Id`);

--
-- Indexen voor tabel `tag_question`
--
ALTER TABLE `tag_question`
  ADD PRIMARY KEY (`SubTagId`,`QuestionTitle`),
  ADD KEY `QuestionTitle` (`QuestionTitle`);

--
-- Indexen voor tabel `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `File` (`File`),
  ADD KEY `QuestionTitle` (`QuestionTitle`),
  ADD KEY `AccountId` (`AccountId`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `account`
--
ALTER TABLE `account`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `comment`
--
ALTER TABLE `comment`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `like`
--
ALTER TABLE `like`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `subtag`
--
ALTER TABLE `subtag`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tag`
--
ALTER TABLE `tag`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `video`
--
ALTER TABLE `video`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`MembershipName`) REFERENCES `membership` (`Name`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `bookmark`
--
ALTER TABLE `bookmark`
  ADD CONSTRAINT `bookmark_ibfk_1` FOREIGN KEY (`AccountId`) REFERENCES `account` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookmark_ibfk_2` FOREIGN KEY (`QuestionTitle`) REFERENCES `question` (`Title`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`VideoId`) REFERENCES `video` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`AccountId`) REFERENCES `account` (`Id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`AccountId`) REFERENCES `account` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`CommentId`) REFERENCES `comment` (`Id`),
  ADD CONSTRAINT `like_ibfk_3` FOREIGN KEY (`VideoId`) REFERENCES `video` (`Id`);

--
-- Beperkingen voor tabel `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`AccountId`) REFERENCES `account` (`Id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `subtag`
--
ALTER TABLE `subtag`
  ADD CONSTRAINT `subtag_ibfk_1` FOREIGN KEY (`TagId`) REFERENCES `tag` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `tag_question`
--
ALTER TABLE `tag_question`
  ADD CONSTRAINT `tag_question_ibfk_1` FOREIGN KEY (`SubTagId`) REFERENCES `subtag` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tag_question_ibfk_2` FOREIGN KEY (`QuestionTitle`) REFERENCES `question` (`Title`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `video_ibfk_1` FOREIGN KEY (`QuestionTitle`) REFERENCES `question` (`Title`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `video_ibfk_2` FOREIGN KEY (`AccountId`) REFERENCES `account` (`Id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
