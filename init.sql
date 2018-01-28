SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `answers` (
`answerID` int(11) NOT NULL,
  `pollID` varchar(8) NOT NULL,
  `text` text NOT NULL,
  `votes` int(11) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `polls` (
  `pollID` varchar(8) NOT NULL,
  `question` text NOT NULL,
  `created` datetime NOT NULL,
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `allowMultiVoting` tinyint(4) DEFAULT '0',
  `allowComments` tinyint(4) NOT NULL DEFAULT '0',
  `randomAnswerOrder` tinyint(4) NOT NULL DEFAULT '1',
  `creatorIP` varchar(15) NOT NULL,
  `votes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `runoff` (
`runoffID` int(11) NOT NULL,
  `pollID` varchar(8) NOT NULL,
  `gtID` int(11) NOT NULL,
  `ltID` int(11) NOT NULL,
  `votes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `voters` (
  `voterID` varchar(10) NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `votes` (
`voteID` int(11) NOT NULL,
  `voterID` varchar(10) NOT NULL,
  `pollID` varchar(8) NOT NULL,
  `answerID` int(11) NOT NULL,
  `vote` int(11) NOT NULL,
  `voteTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `answers`
 ADD PRIMARY KEY (`answerID`);

ALTER TABLE `polls`
 ADD PRIMARY KEY (`pollID`);

ALTER TABLE `runoff`
 ADD PRIMARY KEY (`runoffID`);

ALTER TABLE `voters`
 ADD PRIMARY KEY (`voterID`);

ALTER TABLE `votes`
 ADD PRIMARY KEY (`voteID`);


ALTER TABLE `answers`
MODIFY `answerID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `runoff`
MODIFY `runoffID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `votes`
MODIFY `voteID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
