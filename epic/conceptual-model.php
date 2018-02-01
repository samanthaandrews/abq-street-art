<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Conceptual Model</title>
	</head>
	<body>
		<h1>ABQ Art Walk Conceptual Model</h1>
		<h3>Art</h3>
		<ul>
			<li>artId *primary key(the database has objectId and artCode, should these be included?)</li>
            <li>artAddress</li>
			<li>artArtist</li>
            <li>artImageUrl</li>
            <li>artJpgUrl</li>
            <li>artLocation</li>
            <li>artTitle</li>
            <li>artType</li>
			<li>artX</li>
			<li>artY</li>
            <li>artYear</li>
		</ul>
		<h3>Profile</h3>
		<ul>
			<li>profileId *primary key</li>
			<li>profileActivationToken (possibly not required)</li>
			<li>profileEmail</li>
			<li>profileFullName</li>
			<li>profileHash (possibly not required)</li>
			<li>profileSalt (possibly not required)</li>
		</ul>
		<h3>Bookmark</h3>
		<ul>
			<li>bookmarkArtId *foreign key</li>
			<li>bookmardProfileId *foreign key</li>
		</ul>
		<h3>Comment</h3>
		<ul>
			<li>commentId *primary key(i think this is a try hard?)</li>
			<li>commentArtId *foreign key</li>
			<li>commentProfileId *foreign key</li>
			<li>commentContent</li>
			<li>commentDate</li>
		</ul>
	</body>
</html>