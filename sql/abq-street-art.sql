ALTER DATABASE mschmitt5 CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS art;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS bookmark;
DROP TABLE IF EXISTS comment;

CREATE TABLE art (
  artId BINARY(16) NOT NULL,
  artArtist
  artAddress
  artYear
  artTitle
  artLocation
  artX
  artY
  artType
  artImageUrl
  artJpgUrl
  PRIMARY KEY(artId)
);

CREATE TABLE profile (
  profileId BINARY(16) NOT NULL,
  profileActivationToken
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
  INDEX(clapArticleId),
  INDEX(clapProfileId),
  FOREIGN KEY(clapArticleId) REFERENCES article(articleId),
  FOREIGN KEY(clapProfileId) REFERENCES profile(profileId),
  PRIMARY KEY(clapId)
);

CREATE TABLE comment(
  clapId BINARY(16) NOT NULL,
  clapArticleId BINARY(16) NOT NULL,
  clapProfileId BINARY(16) NOT NULL,
  INDEX(clapArticleId),
  INDEX(clapProfileId),
  FOREIGN KEY(clapArticleId) REFERENCES article(articleId),
  FOREIGN KEY(clapProfileId) REFERENCES profile(profileId),
  PRIMARY KEY(clapId)
);