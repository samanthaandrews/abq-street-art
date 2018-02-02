ALTER DATABASE ngustafson CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS art;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS bookmark;
DROP TABLE IF EXISTS comment;


-- create the art entity
CREATE TABLE art (
  -- artId is the primary key
  artId BINARY(16) NOT NULL,
  artAddress VARCHAR(200) NOT NULL,
  artArtist VARCHAR(200) NOT NULL,
  artImageUrl VARCHAR(200) NOT NULL,
  artLat DECIMAL(9,6) NOT NULL,
  artLocation VARCHAR(200),
  artLong VARCHAR(9,6) NOT NULL ,
  artTitle VARCHAR(200),
  artType VARCHAR(200),
  artyear CHAR(4),
  PRIMARY KEY(artId)
);

CREATE TABLE profile (
  profileId BINARY(16) NOT NULL,
  profileActivationToken,
  profileEmail VARCHAR(128) NOT NULL,
  profileHash CHAR(128) NOT NULL,
  profileFullName VARCHAR(32) NOT NULL,
  profileSalt CHAR(64) NOT NULL,
  UNIQUE(profileEmail),
  PRIMARY KEY(profileId)
);

CREATE TABLE bookmark(
  bookmarkArtId BINARY(16) NOT NULL,
  bookmarkProfileId BINARY(16) NOT NULL,
  INDEX(bookmarkArtId),
  INDEX(bookmarkProfileId),
  FOREIGN KEY(bookmarkArtId) REFERENCES art(artId),
  FOREIGN KEY(bookmarkProfileId) REFERENCES profile(profileId),
  PRIMARY KEY(bookmarkArtId, bookmarkProfileId)
);

CREATE TABLE comment(
  commentId BINARY(16) NOT NULL,
  commentArtId BINARY(16) NOT NULL,
  commentProfileId BINARY(16) NOT NULL,
  commentContent VARCHAR(65535) NOT NULL,
  commentDateTime DATETIME NOT NULL,
  INDEX(commentArtId),
  INDEX(commentProfileId),
  FOREIGN KEY(commentArtId) REFERENCES art(artId),
  FOREIGN KEY(commentProfileId) REFERENCES profile(profileId),
  PRIMARY KEY(commentId)
);