<!DOCTYPE html>
<html>
<body>


<a align="center" href="index.php">
        <h1>CS143 Poor Man's IMDB</h1>
</a>

<h2 align="center">Movie Information</h2>

<?php
require_once "config.php";

$movie_id = $_GET['identifier'];


// MOVIE INFO
$movie_info = $db->prepare("SELECT title, year, rating, company FROM Movie WHERE id=?");

$movie_info->bind_param('s', $movie_id);
if(!$movie_info->execute()) {
	$errmsg = $db->error;
	print "Query failed: $errmsg <br>";
	exit(1);
}
?>


<h3 align="center">Movie</h3>
<div style="display: block; text-align: center;">
<div style="display: inline-block; text-align: left;">
<?php
$movie_info->bind_result($returned_title, $returned_year, $returned_rating, $returned_company);
while($movie_info->fetch()) {
	echo 'Title: ' . $returned_title . ' (' . $returned_year . ') ' . '<br>';
	echo 'Producer: ' . $returned_company . '<br>';
	echo 'MPAA Rating: ' . $returned_rating . '<br>';
}


// MOVIE DIRECTOR
$movie_director = $db->prepare("SELECT T1.first, T1.last, T1.dob FROM Director AS T1 INNER JOIN (SELECT did FROM MovieDirector WHERE mid=?) AS T2 ON T1.id = T2.did");

$movie_director->bind_param('s', $movie_id);
if(!$movie_director->execute()) {
	$errmsg = $db->error;
	print "Query failed: $errmsg <br>";
	exit(1);
}

$movie_director->bind_result($returned_dfirst, $returned_dlast, $returned_ddob);
echo 'Director: ';
while($movie_director->fetch()) {
	echo $returned_dfirst . ' ' . $returned_dlast . ' (' . $returned_ddob . ')';
}
echo '<br>';


// MOVIE GENRES
$movie_genres = $db->prepare("SELECT genre FROM MovieGenre WHERE mid=?");

$movie_genres->bind_param('s', $movie_id);
if(!$movie_genres->execute()) {
	$errmsg = $db->error;
	print "Query failed: $errmsg <br>";
	exit(1);
}

$movie_genres->bind_result($returned_genre);
echo 'Genre: ';
while($movie_genres->fetch()) {
	echo $returned_genre . ' ';
}
echo '<br>';
?>
</div>
</div><br>


<?php
// MOVIES ACTORS
$movie_actors = $db->prepare("SELECT T1.id, T1.first, T1.last, T2.role FROM Actor AS T1 INNER JOIN (SELECT aid, role FROM MovieActor WHERE mid=?) AS T2 ON T1.id = T2.aid");

$movie_actors->bind_param('s', $movie_id);
if(!$movie_actors->execute()) {
	$errmsg = $db->error;
	print "Query failed: $errmsg <br>";
	exit(1);
}
?>


<div align="center">
<h3>Actors and Roles</h3>
<table align="center" style="border: 1px solid black; border-collapse: collapse;">
<?php
echo '<tr align="center">
        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Actor Name</th>
	<th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Role</th></tr>';

$movie_actors->bind_result($returned_aid, $returned_afirst, $returned_alast, $returned_role);
while($movie_actors->fetch()) {
	echo '<tr align="left"><td style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;"><a href="actor.php?identifier=' 
		. $returned_aid . '">' . $returned_afirst . ' ' . $returned_alast . '</a></td>';
	echo '<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">"' 
		. $returned_role . '</td></tr>';
}
?>
</table>
</div><br>


<div align="center">
<h3>Reviews</h3>
<?php
// MOVIE RATING
$rating = $db->prepare("SELECT AVG(rating), COUNT(rating) FROM Review WHERE mid=? GROUP BY mid");

$rating->bind_param('s', $movie_id);
if(!$rating->execute()) {
	$errmsg = $db->error;
	print "Query failed: $errmsg <br>";
	exit(1);
}

$rating->bind_result($returned_avg, $returned_count);
while($rating->fetch()) {
	echo '<a style="font-size: 19px">The average rating for this movie is ' . $returned_avg . ' out of 5 based on ' . $returned_count . ' reviews.</a><br><br>';
}


// LEAVE A REVIEW
echo '<a style="font-size: 19px" href="review.php?MovieID=' . $movie_id . '">Leave a comment</a><br><br>';
?>
</div><br>


<div style="display: block; text-align: center;">
<div style="display: inline-block; text-align: left;">
<?php
// MOVIE REVIEWS
$reviews = $db->prepare("SELECT name, time, rating, comment FROM Review WHERE mid=?");

$reviews->bind_param('s', $movie_id);
if(!$reviews->execute()) {
	$errmsg = $db->error;
	print "Query failed: $errmsg <br>";
	exit(1);
}

$reviews->bind_result($returned_name, $returned_time, $returned_score, $returned_comment);
while($reviews->fetch()) {
	echo $returned_name . ' rates this movie a ' . $returned_score . ' and left a review at ' . $returned_time . '.<br>';
        echo 'Comment: ' . $returned_comment . '<br><br>';
}
?>
</div>
</div><br>


<?php
// CLEANUP
$actor_info->free_result();
$actors_movies->free_result();

$db->close();
exit(0);
?>

</body>
</html>
