<!DOCTYPE html>
<html>
<body>

<a href="index.php">
        <h1 align="center">CS143 Poor Man's IMDB</h1>
</a>

<?php
date_default_timezone_set('UTC');

require_once "config.php";

$movie_id = $_GET['MovieID'];


// MOVIE TITLE
$movie_info = $db->prepare("SELECT title, year FROM Movie WHERE id=?");

$movie_info->bind_param('s', $movie_id);
if(!$movie_info->execute()) {
        $errmsg = $db->error;
        print "Query failed: $errmsg <br>";
        exit(1);
}

$movie_info->bind_result($returned_title, $returned_year);
while($movie_info->fetch()) {
        echo '<h2 align="center">' . $returned_title . ' (' . $returned_year . ')</h2>';
}

// REVIEW FORM
echo '<h3 align="center">Review</h3>';
echo '
<div align="center">
<form action="" method="post" style="display: inline-block; text-align: left;">
	<label for="name">Your name</label><br>
	<input id="name" type="text" name="name" value="Anonymous" size="30px"><br><br>
	<label for="rating">Your rating</label><br>
	<select id="rating" name="rating" style="width: 45px">
  		<option value="1">1</option>
  		<option value="2">2</option>
  		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
	</select><br><br>
	<label for="comment">Your comment</label><br>
	<textarea id="comment" name="comment" cols="40" rows="5"></textarea><br>
	<input type="submit" value="Post review"> <a style="padding-left: 20px" href="movie.php?identifier=' . $movie_id . '">cancel</a><br>
</form>
</div><br>
';

$name = $_POST['name'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];


// INSERT REVIEW
if(isset($name, $rating, $comment)) {
        $review = $db->prepare("INSERT INTO Review (name, time, mid, rating, comment) VALUES (?, ?, ?, ?, ?)");

        $review->bind_param('sssss', $name, date("Y-m-d h:i:s"), $movie_id, $rating, $comment);
        if(!$review->execute()) {
                $errmsg = $db->error;
                print "Query failed: $errmsg <br>";
                exit(1);
	} else {
		echo '<div align="center"><div style="display: inline-block; text-align: left; padding-right: 95px">';
                echo 'Success! Your review has been posted.<br>';
		echo '<a href="movie.php?identifier=' . $movie_id . '">Return to movie page</a><br>';
		echo '</div></div>';
        }
}


// CLEANUP
$review->close();

$db->close();
exit(0);
?>

</body>
</html>
