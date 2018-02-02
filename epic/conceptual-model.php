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
			<li>artId *primary key</li>
			<li>artAddress</li>
			<li>artArtist</li>
			<li>artImageUrl (we will be using the "jpg url"/static image data for this)</li>
			<li>artLat</li>
			<li>artLocation</li>
			<li>artLong</li>
			<li>artTitle</li>
			<li>artType</li>
			<li>artYear</li>
		</ul>
		<h3>Profile</h3>
		<ul>
			<li>profileId *primary key</li>
			<li>profileActivationToken</li>
			<li>profileEmail</li>
			<li>profileHash</li>
			<li>profileSalt</li>
			<li>profileUserName</li>
		</ul>
		<h3>Bookmark</h3>
		<ul>
			<li>bookmarkArtId *foreign key</li>
			<li>bookmarkProfileId *foreign key</li>
		</ul>
		<h3>Comment</h3>
		<ul>
			<li>commentId *primary key</li>
			<li>commentArtId *foreign key</li>
			<li>commentProfileId *foreign key</li>
			<li>commentContent</li>
			<li>commentDateTime</li>
		</ul>
	</body>
</html>