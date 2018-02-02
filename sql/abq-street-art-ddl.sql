ALTER DATABASE ngustafson CHARACTER SET utf8 COLLATE utf8_unicode_ci;

# DROP TABLE IF EXISTS art;
# DROP TABLE IF EXISTS profile;
# DROP TABLE IF EXISTS bookmark;
# DROP TABLE IF EXISTS comment;


-- create the art entity
CREATE TABLE art (
  -- artId is the primary key
  artId BINARY(16) NOT NULL,
  artAddress VARCHAR(200) NOT NULL,
  artArtist VARCHAR(200),
  artImageUrl VARCHAR(200),
  artLat DECIMAL(12,9) NOT NULL,
  artLocation VARCHAR(200),
  artLong DECIMAL(12,9) NOT NULL,
  artTitle VARCHAR(200),
  artType VARCHAR(200),
  artYear SMALLINT UNSIGNED,
  PRIMARY KEY(artId)
);

CREATE TABLE profile (
  -- profileId is the primary key
  profileId BINARY(16) NOT NULL,
  profileActivationToken CHAR(32) NOT NULL,
  profileEmail VARCHAR(128) NOT NULL,
  profileHash CHAR(128) NOT NULL,
  profileSalt CHAR(64) NOT NULL,
  profileUserName VARCHAR(32) NOT NULL,
  UNIQUE(profileEmail),
  UNIQUE (profileUserName),
  PRIMARY KEY(profileId)
);

CREATE TABLE bookmark (
  bookmarkArtId BINARY(16) NOT NULL,
  bookmarkProfileId BINARY(16) NOT NULL,
  -- we index the foreign keys because they increase performance for static entities in the database
  INDEX(bookmarkArtId),
  INDEX(bookmarkProfileId),
  FOREIGN KEY(bookmarkArtId) REFERENCES art(artId),
  FOREIGN KEY(bookmarkProfileId) REFERENCES profile(profileId),
  PRIMARY KEY(bookmarkArtId, bookmarkProfileId)
);

CREATE TABLE comment (
  commentId BINARY(16) NOT NULL,
  commentArtId BINARY(16) NOT NULL,
  commentProfileId BINARY(16) NOT NULL,
  commentContent VARCHAR(4096) NOT NULL,
  commentDateTime DATETIME(6) NOT NULL,
  INDEX(commentArtId),
  INDEX(commentProfileId),
  FOREIGN KEY(commentArtId) REFERENCES art(artId),
  FOREIGN KEY(commentProfileId) REFERENCES profile(profileId),
  PRIMARY KEY(commentId)
);