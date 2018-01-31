ALTER DATABASE mschmitt5 CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS art;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS bookmark;
DROP TABLE IF EXISTS comment;

CREATE TABLE art (
  artId BINARY(16) NOT NULL,
  artArtist,
  artAddress,
  artYear,
  artTitle,
  artLocation,
  artX,
  artY,
  artType,
  artImageUrl,
  artJpgUrl,
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
  commentArtId BINARY(16) NOT NULL,
  commentProfileId BINARY(16) NOT NULL,
  INDEX(commentArtId),
  INDEX(commentProfileId),
  FOREIGN KEY(commentArtId) REFERENCES art(artId),
  FOREIGN KEY(commentProfileId) REFERENCES profile(profileId),
  PRIMARY KEY(commentArtId, commentProfileId)
);