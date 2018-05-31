SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `answers` (
`answerID` int(11) NOT NULL,
  `pollID` varchar(8) NOT NULL,
  `text` text NOT NULL,
  `votes` int(11) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=757 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `polls` (
  `pollID` varchar(8) NOT NULL,
  `customSlug` varchar(16) DEFAULT NULL,
  `question` text NOT NULL,
  `created` datetime NOT NULL,
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `verifiedVoting` tinyint(4) NOT NULL DEFAULT '0',
  `verifiedVotingType` varchar(3) DEFAULT NULL,
  `allowComments` tinyint(4) NOT NULL DEFAULT '0',
  `randomAnswerOrder` tinyint(4) NOT NULL DEFAULT '1',
  `creatorIP` varchar(15) NOT NULL,
  `surveyID` varchar(8) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `startTime` datetime,
  `endTime` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `surveys` (
  `surveyID` varchar(8) NOT NULL,
  `customSlug` varchar(16) DEFAULT NULL,
  `title` text NOT NULL,
  `created` datetime NOT NULL,
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `verifiedVoting` tinyint(4) NOT NULL DEFAULT '0',
  `verifiedVotingType` varchar(3) DEFAULT NULL,
  `allowComments` tinyint(4) NOT NULL DEFAULT '0',
  `randomOrder` tinyint(4) NOT NULL DEFAULT '1',
  `creatorIP` varchar(15) NOT NULL,
  `votes` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `verbage` varchar(2) NOT NULL,
  `startTime` datetime,
  `endTime` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `runoff` (
`runoffID` int(11) NOT NULL,
  `pollID` varchar(8) NOT NULL,
  `gtID` int(11) NOT NULL,
  `ltID` int(11) NOT NULL,
  `votes` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13731 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `voterKeys` (
  `pollID` varchar(10) NOT NULL,
  `voterKey` varchar(12) NOT NULL,
  `invalid` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `voters` (
  `voterID` varchar(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `votes` (
`voteID` int(11) NOT NULL,
  `voterID` varchar(10) NOT NULL,
  `pollID` varchar(8) NOT NULL,
  `answerID` int(11) NOT NULL,
  `vote` int(11) NOT NULL,
  `voteTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=4947 DEFAULT CHARSET=utf8;


ALTER TABLE `answers`
 ADD PRIMARY KEY (`answerID`);

ALTER TABLE `polls`
 ADD PRIMARY KEY (`pollID`);

ALTER TABLE `runoff`
 ADD PRIMARY KEY (`runoffID`);

ALTER TABLE `voterKeys`
 ADD PRIMARY KEY (`pollID`,`voterKey`);

ALTER TABLE `voters`
 ADD PRIMARY KEY (`voterID`);

ALTER TABLE `votes`
 ADD PRIMARY KEY (`voteID`);


ALTER TABLE `answers`
MODIFY `answerID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=757;
ALTER TABLE `runoff`
MODIFY `runoffID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13731;
ALTER TABLE `votes`
MODIFY `voteID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4947;
