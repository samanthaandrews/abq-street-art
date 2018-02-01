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
        <li>artId</li>
        <li>artArtist</li>
        <li>artAddress</li>
        <li>artYear</li>
        <li>artTitle</li>
        <li>artLocation</li>
        <li>artX</li>
        <li>artY</li>
        <li>artType</li>
        <li>artImageUrl</li>
        <li>artJpgUrl</li>
    </ul>
    <h3>Profile</h3>
    <ul>
        <li>profileId</li>
        <li>profileActivationToken (possibly not required)</li>
        <li>profileEmail</li>
        <li>profileFullName</li>
        <li>profileHash (possibly not required)</li>
        <li>profileSalt (possibly not required)</li>
    </ul>
    <h3>Bookmark</h3>
    <ul>
        <li>bookmarkArtId</li>
        <li>bookmardProfileId</li>
    </ul>
    <h3>Comment</h3>
    <ul>
        <li>commentId (is this a try hard?)</li>
        <li>commentArtId</li>
        <li>commentProfileId</li>
        <li>commentContent</li>
        <li>commentDateTime</li>
    </ul>
</body>
</html>