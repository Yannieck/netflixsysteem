CREATE DATABASE IF NOT EXISTS `null pointer videos`;

USE `null pointer videos`;

CREATE TABLE IF NOT EXISTS `membership` (
	Name VARCHAR(6) NOT NULL,
    Price DOUBLE(2,2) NOT NULL,
    
    CONSTRAINT PRIMARY KEY (Name)
);

CREATE TABLE IF NOT EXISTS `account` (
	Id INT NOT NULL AUTO_INCREMENT,
    MembershipName VARCHAR(6) NOT NULL,
    Name VARCHAR(80)DEFAULT 'Unknown',
    Username VARCHAR(40) NOT NULL,
    Email VARCHAR(300) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Biography VARCHAR(100),
    Photo VARCHAR(150),
    GithubLink VARCHAR(40),
    
    CONSTRAINT PRIMARY KEY (Id),
    CONSTRAINT FOREIGN KEY (MembershipName) REFERENCES membership(Name) ON DELETE NO ACTION ON UPDATE CASCADE,
   	CONSTRAINT UNIQUE (Username),
    CONSTRAINT UNIQUE (Email),
    CONSTRAINT UNIQUE (GithubLink)
);

CREATE TABLE IF NOT EXISTS `question` (
    Id INT NOT NULL AUTO_INCREMENT,
	Title VARCHAR(255) NOT NULL,
    AccountId INT NOT NULL,
    Content VARCHAR(8000) NOT NULL,
    AskDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT PRIMARY KEY (Id),
    CONSTRAINT UNIQUE (Title),
    CONSTRAINT FOREIGN KEY (AccountId) REFERENCES account(Id) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `video` (
	Id INT NOT NULL AUTO_INCREMENT,
    -- QuestionTitle VARCHAR(100) NOT NULL,
    QuestionId INT NOT NULL,
    AccountId INT NOT NULL,
    Description VARCHAR(8000),
    UploadDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    File VARCHAR(150) NOT NULL,
    
    CONSTRAINT PRIMARY KEY (Id),
    CONSTRAINT FOREIGN KEY (QuestionId) REFERENCES question(Id) ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (AccountId) REFERENCES account(Id) ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT UNIQUE (File)
);

CREATE TABLE IF NOT EXISTS  `comment` (
	Id INT NOT NULL AUTO_INCREMENT,
    VideoId INT NOT NULL,
    AccountId INT NOT NULL,
    Content VARCHAR(255) NOT NULL,
 
    CONSTRAINT PRIMARY KEY (Id),
    CONSTRAINT FOREIGN KEY (VideoId) REFERENCES video(Id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (AccountId) REFERENCES account(Id) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `bookmark` (
	AccountId INT NOT NULL,
    -- QuestionTitle VARCHAR(100) NOT NULL,
    QuestionId INT NOT NULL,
    
    CONSTRAINT PRIMARY KEY (AccountId, QuestionId),
    CONSTRAINT FOREIGN KEY (AccountId) REFERENCES account(Id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (QuestionId) REFERENCES question(Id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `like` (
    Id INT NOT NULL AUTO_INCREMENT,
	AccountId INT NOT NULL,
    CommentId INT,
    VideoId INT,
    Type BOOLEAN NOT NULL,
    
    CONSTRAINT PRIMARY KEY (Id),
    CONSTRAINT FOREIGN KEY (AccountId) REFERENCES account(Id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (CommentId) REFERENCES `comment`(Id),
    CONSTRAINT FOREIGN KEY (VideoId) REFERENCES video(Id)
);

CREATE TABLE IF NOT EXISTS `tag` (
	Id INT NOT NULL AUTO_INCREMENT,
    Category VARCHAR(15) NOT NULL,
    
    CONSTRAINT PRIMARY KEY (Id)
);

CREATE TABLE IF NOT EXISTS `subtag` (
	Id INT NOT NULL AUTO_INCREMENT,
    TagId INT NOT NULL,
    SubCategory VARCHAR(25) NOT NULL,
    
    CONSTRAINT PRIMARY KEY (Id),
    CONSTRAINT FOREIGN KEY (TagId) REFERENCES tag(Id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `tag_question` (
	SubTagId INT NOT NULL,
    QuestionId INT NOT NULL,
    
    CONSTRAINT PRIMARY KEY (SubTagId, QuestionId),
    CONSTRAINT FOREIGN KEY (SubTagId) REFERENCES subtag(Id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (QuestionId) REFERENCES question(Id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `membership` (`Name`, `Price`) VALUES ('Junior', 09.99);
INSERT INTO `membership` (`Name`, `Price`) VALUES ('Senior', 14.99);
INSERT INTO `membership` (`Name`) VALUES ('Admin');
INSERT INTO `membership` (`Name`) VALUES ('Prof');

INSERT INTO `account` (`MembershipName`,`Name`,`Username`,`Email`,`Password`) VALUES ('Admin', 'Admin', 'Developers', 'developer@gmail.com', '$2y$10$tc1ctq28T5bm9DpO42rvzeI0PFGecEhUr1l1Gb.zcWezqj5VA06/W');

INSERT INTO `question` (`Title`,`AccountId`,`Content`,`AskDate`) VALUES ("How to change parser titles when using Argparse without modifying internal variables?", 1, "

I'm using Python's argparse module to create a CLI for my application. I've made a subparsers variable to store the parsers for each command, but when I can't find a way to change the title of the subparsers without modifying parser's (the main ArgumentParser's) internal variables.", "2021-12-14 13:19:20");

INSERT INTO `question` (`Title`,`AccountId`,`Content`,`AskDate`) VALUES ("What are some ways to avoid String.substring from returning substring with invalid unicode character", 1, "

Recently, only I notice that, it is possible for substring to return string with invalid unicode character.

For instance

public class Main {

    public static void main(String[] args) {
        String text = '🥦_Salade verte'»

        /* We should avoid using endIndex = 1, as it will cause an invalid character in the returned substring. */
        // 1 : ?
        System.out.println('1 : ' + text.substring(0, 1))»

        // 2 : 🥦
        System.out.println('2 : ' + text.substring(0, 2))»

        // 3 : 🥦_
        System.out.println('3 : ' + text.substring(0, 3))»

        // 4 : 🥦_S
        System.out.println('4 : ' + text.substring(0, 4))»
    }
}

I was wondering, when trimming a long string with String.substring, what are some good ways to avoid the returned substring from containing invalid unicode?
", "2021-04-04 18:57:19");

INSERT INTO `question` (`Title`,`AccountId`,`Content`,`AskDate`) VALUES ("Why can't a const mutable lambda with an auto& parameter be invoked?", 1, "

#include <type_traits>

int main()
{
    auto f1 = [](auto&) mutable {}»
    static_assert(std::is_invocable_v<decltype(f1), int&>)» // ok

    auto const f2 = [](auto&) {}»
    static_assert(std::is_invocable_v<decltype(f2), int&>)» // ok

    auto const f3 = [](auto&) mutable {}»
    static_assert(std::is_invocable_v<decltype(f3), int&>)» // failed
}

See demo

Why can't a const mutable lambda take a reference argument?
", "2021-12-11 13:19:30");

INSERT INTO `tag` (`Id`, `Category`) VALUES (1, 'Python'), (2, 'Java'), (3, 'C++');

INSERT INTO `subtag` (`Id`, `TagId`, `SubCategory`) VALUES (1, 1, 'python'), (2, 1, 'parsing'), (3, 1, 'command-line'), (4, 1, 'argparse'), (5, 2, 'java'), (6, 2, 'android'), (7, 3, 'c++'), (8, 3, 'lambda'), (9, 3, 'c++20'), (10, 2, 'typetraits'), (11, 3, 'static-assert');
INSERT INTO `tag_question` (`SubTagId`, `QuestionId`) VALUES (1, 1), (2, 1), (3, 1), (4, 1), (5, 2), (6, 2), (7, 3), (8, 3), (9, 3), (10, 3), (11, 3) 
