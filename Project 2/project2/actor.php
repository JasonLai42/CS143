<!DOCTYPE html>
<html>
<body>


<a href="index.php">
	<h1 align="center">CS143 Poor Man's IMDB</h1>
</a>

<h2 align="center">Actor Information</h2>

<?php
require_once "config.php";

$actor_id = $_GET['identifier'];


// ACTOR INFO
$actor_info = $db->prepare("SELECT first, last, sex, dob, dod FROM Actor WHERE id=?");

$actor_info->bind_param('s', $actor_id);
if(!$actor_info->execute()) {
    $errmsg = $db->error;
    print "Query failed: $errmsg <br>";
    exit(1);
}
?>


<div align="center">
<h3>Actor</h3>
<table style="border: 1px solid black; border-collapse: collapse;">
<?php
echo '<tr align="left">
	<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">Name</th>
	<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">Sex</th>
	<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">Date of Birth</th>
	<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">Date of Death</th></tr>';

$actor_info->bind_result($returned_first, $returned_last, $returned_sex, $returned_dob, $returned_dod);
while($actor_info->fetch()) {
	echo '<tr><td style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">' 
		. $returned_first . ' ' . $returned_last . '</td>';
	echo '<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">' 
		. $returned_sex . '</td>';
	echo '<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">' 
		. $returned_dob . '</td>';
	if($returned_dod) {       
		echo '<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">' 
			. $returned_dod . '</td></tr>';
	} else {
		echo '<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">Not Dead Yet</td></tr>';
	}
}
?>
</table>
</div><br>


<?php
// ACTOR'S MOVIES
$actors_movies = $db->prepare("SELECT T1.id, T1.title, T2.role FROM Movie AS T1 INNER JOIN (SELECT mid, role FROM MovieActor WHERE aid=?) AS T2 ON T1.id = T2.mid");

$actors_movies->bind_param('s', $actor_id);
if(!$actors_movies->execute()) {
    $errmsg = $db->error;
    print "Query failed: $errmsg <br>";
    exit(1);
}
?>


<div align="center">
<h3>Movies and Roles</h3>
<table align="center" style="border: 1px solid black; border-collapse: collapse;">
<?php
echo '<tr align="center">
        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Role</th>
        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Movie Title</th></tr>';

$actors_movies->bind_result($returned_mid, $returned_title, $returned_role);
while($actors_movies->fetch()) {
	echo '<tr align="left"><td style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;">"' 
		. $returned_role . '"</td>';
	echo '<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; padding-left: 5px; padding-right: 10px;"><a href="movie.php?identifier=' 
		. $returned_mid . '">' . $returned_title . '</a></td></tr>';
}
?>
</table>
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
