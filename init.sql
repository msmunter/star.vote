SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `answers` (
  `answerID` int(11) NOT NULL,
  `pollID` varchar(8) NOT NULL,
  `text` text NOT NULL,
  `votes` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `imgur` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `polls` (
  `pollID` varchar(8) NOT NULL,
  `userID` int(11) DEFAULT '0',
  `surveyID` varchar(8) DEFAULT NULL,
  `customSlug` varchar(16) DEFAULT NULL,
  `question` text NOT NULL,
  `created` datetime NOT NULL,
  `startTime` datetime NOT NULL,
  `endTime` datetime DEFAULT NULL,
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `kioskMode` tinyint(4) NOT NULL DEFAULT '0',
  `verifiedVoting` tinyint(4) NOT NULL DEFAULT '0',
  `verifiedVotingType` varchar(3) DEFAULT NULL,
  `numWinners` tinyint(4) NOT NULL DEFAULT '1',
  `allowComments` tinyint(4) NOT NULL DEFAULT '0',
  `randomAnswerOrder` tinyint(4) NOT NULL DEFAULT '1',
  `creatorIP` varchar(15) NOT NULL,
  `votes` int(11) NOT NULL,
  `blind` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `runoff` (
  `runoffID` int(11) NOT NULL,
  `pollID` varchar(8) NOT NULL,
  `gtID` int(11) NOT NULL,
  `ltID` int(11) NOT NULL,
  `votes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `surveys` (
  `surveyID` varchar(8) NOT NULL,
  `userID` int(11) NOT NULL,
  `verbage` varchar(2) NOT NULL DEFAULT 'su',
  `title` text NOT NULL,
  `created` datetime NOT NULL,
  `startTime` datetime NOT NULL,
  `endTime` datetime DEFAULT NULL,
  `customSlug` varchar(16) DEFAULT NULL,
  `randomOrder` tinyint(4) NOT NULL,
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `kioskMode` tinyint(4) NOT NULL DEFAULT '0',
  `verifiedVoting` tinyint(4) NOT NULL DEFAULT '0',
  `verifiedVotingType` varchar(3) DEFAULT NULL,
  `creatorIP` varchar(15) NOT NULL,
  `votes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `surveyvoterkeys` (
  `surveyID` varchar(10) NOT NULL,
  `voterKey` varchar(16) NOT NULL,
  `createdTime` datetime NOT NULL,
  `voteTime` datetime DEFAULT NULL,
  `voterID` varchar(10) NOT NULL,
  `invalid` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tokens` (
  `userID` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `pass` varchar(256) NOT NULL,
  `admin_level` int(3) NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `disabled` tinyint(4) NOT NULL DEFAULT '0',
  `firstName` varchar(32) NOT NULL,
  `lastName` varchar(32) NOT NULL,
  `email` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `voterkeys` (
  `pollID` varchar(10) NOT NULL,
  `voterKey` varchar(16) NOT NULL,
  `createdTime` datetime NOT NULL,
  `voteTime` datetime DEFAULT NULL,
  `voterID` varchar(10) DEFAULT NULL,
  `invalid` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `voters` (
  `voterID` varchar(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `votes` (
  `voteID` int(11) NOT NULL,
  `voterID` varchar(10) NOT NULL,
  `pollID` varchar(8) NOT NULL,
  `answerID` int(11) NOT NULL,
  `vote` int(11) NOT NULL,
  `voteTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `answers`
  ADD PRIMARY KEY (`answerID`),
  ADD KEY `pollID` (`pollID`);

ALTER TABLE `polls`
  ADD PRIMARY KEY (`pollID`);

ALTER TABLE `runoff`
  ADD PRIMARY KEY (`runoffID`);

ALTER TABLE `surveys`
  ADD PRIMARY KEY (`surveyID`);

ALTER TABLE `surveyvoterkeys`
  ADD PRIMARY KEY (`surveyID`,`voterKey`);

ALTER TABLE `tokens`
  ADD UNIQUE KEY `token` (`token`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

ALTER TABLE `voterkeys`
  ADD PRIMARY KEY (`pollID`,`voterKey`);

ALTER TABLE `voters`
  ADD PRIMARY KEY (`voterID`);

ALTER TABLE `votes`
  ADD PRIMARY KEY (`voteID`),
  ADD KEY `voterID` (`voterID`),
  ADD KEY `pollID` (`pollID`),
  ADD KEY `answerID` (`answerID`);


ALTER TABLE `answers`
  MODIFY `answerID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `runoff`
  MODIFY `runoffID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `votes`
  MODIFY `voteID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;