<!DOCTYPE html>
<html>
<body>


<a href="index.php">
	<h1 align="center">CS143 Poor Man's IMDB</h1>
</a>

<form action="index.php" method="get" align="center">
<input size="40" type="text" name="result" placeholder="actor/actress name or movie title">
<input type="submit" value="Search">
</form><br><br>


<?php
require_once "config.php";

// INPUT SEARCH STRING
$search_string = $_GET['result'];

// PARSE SEARCH STRING; SECOND ARG FOR BINDING PARAMS
$search_words = explode(' ', $search_string);
// ARRAY TO CONTAIN SEARCH WORDS TWICE FOR ACTOR FIRST/LAST PARAMS
$twice_search = array();

// FIRST ARG FOR BINDING PARAMS
$types = str_repeat('s', count($search_words));
// THERE'S TWICE AS MANY ARGS FOR ACTOR THAN FOR MOVIE
$twice_types = str_repeat($types, 2);

// BUILD QUERY STRINGS
$actor_query = "SELECT id, first, last, dob FROM Actor WHERE (first LIKE ? OR last LIKE ?)";
$movie_query = "SELECT id, title, year FROM Movie WHERE title LIKE ?";
for($x = 0; $x < count($search_words); $x++) {
	// ADD WILDCARDS
	$search_words[$x] = '%' . $search_words[$x] . '%';
	array_push($twice_search, '%' . $search_words[$x] . '%', '%' . $search_words[$x] . '%');
	// IF >1 WORD
	if($x > 0) {
		$actor_query .= " AND (first LIKE ? OR last LIKE ?)";
		$movie_query .= " AND title LIKE ?";
	}
}
// DISPLAY ORDER
$actor_query .= " ORDER BY last ASC";
$movie_query .= " ORDER BY title ASC";
?>


<?php
// ACTOR SEARCH
$actor_result = $db->prepare($actor_query);

$actor_result->bind_param($twice_types, ...$twice_search);
if(!$actor_result->execute()) {
    $errmsg = $db->error; 
    print "Query failed: $errmsg <br>"; 
    exit(1); 
}
$actor_result->store_result();

echo '<h3 align="center">Actors	- ' . $actor_result->num_rows . ' results</h3>';
?>


<table align="center">
<?php
echo '<tr align="left"><th>Name</th><th>Date of Birth</th></tr>';

$actor_result->bind_result($returned_aid, $returned_first, $returned_last, $returned_dob);
while($actor_result->fetch()) {
	echo '<tr align="left"><td style="padding-right: 50px"><a href="actor.php?identifier=' . $returned_aid . '">' . $returned_first . ' ' . $returned_last . '</a></td>';
        echo '<td><a href="actor.php?identifier=' . $returned_aid . '">' . $returned_dob . '</a></td></tr>';
}
?>
</table><br>


<?php
// MOVIE SEARCH
$movie_result = $db->prepare($movie_query);

$movie_result->bind_param($types, ...$search_words);
if(!$movie_result->execute()) {
    $errmsg = $db->error;
    print "Query failed: $errmsg <br>";
    exit(1);
}
$movie_result->store_result();

echo '<h3 align="center">Movies - ' . $movie_result->num_rows . ' results</h3>';
?>


<table align="center">
<?php
echo '<tr align="left"><th>Title</th><th>Year</th></tr>';

$movie_result->bind_result($returned_mid, $returned_title, $returned_year);
while($movie_result->fetch()) {
	echo '<tr><td style="padding-right: 50px"><a href="movie.php?identifier=' . $returned_mid . '">' . $returned_title . '</a></td>';
        echo '<td><a href="movie.php?identifier=' . $returned_mid . '">' . $returned_year . '</a></td></tr>';
}
?>
</table><br>


<?php
// CLEANUP
$actor_result->free_result();
$movie_result->free_result();

$db->close();
exit(0);
?>


</body>
</html>
