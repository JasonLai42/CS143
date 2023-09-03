<?php
$servername = "localhost";
$username = "cs143";
$password = "";
$dbname = "cs143";

// Connect to database
$db = new mysqli($servername, $username, $password, $dbname);
if ($db->connect_errno > 0) { 
    die('Unable to connect to database [' . $db->connect_error . ']');
}

# get the id parameter from the request
$id = intval($_GET['id']);

# set the Content-Type header to JSON, so that the client knows that we are returning a JSON data
header('Content-Type: application/json');

$laureateArr = array();
$nobelPrizeArr = array();

// Laureate info
$laureate_info = $db->prepare("SELECT id, givenName, familyName, gender, orgName, dob, city, country FROM Laureate WHERE id=?");

$laureate_info->bind_param('i', $id);
if(!$laureate_info->execute()) {
	$errmsg = $db->error;
	print "Query failed: $errmsg <br>";
	exit(1);
}

$laureate_id;
$laureate_info->bind_result($ret_id, $ret_givenName, $ret_familyName, $ret_gender, $ret_orgName, $ret_dob, $ret_city, $ret_country);
while($laureate_info->fetch()) {
	$laureate_id = $ret_id;
	if($ret_orgName == NULL) {
		$laureateArr["id"] = strval($ret_id);
		if($ret_givenName != NULL) { $laureateArr["givenName"]["en"] = $ret_givenName; }
		if($ret_familyName != NULL) { $laureateArr["familyName"]["en"] = $ret_familyName; }
		if($ret_gender != NULL) { $laureateArr["gender"] = $ret_gender; }
		if($ret_dob != NULL) { $laureateArr["birth"]["date"] = $ret_dob; }
		if($ret_city != NULL) { $laureateArr["birth"]["place"]["city"]["en"] = $ret_city; }
		if($ret_country != NULL) { $laureateArr["birth"]["place"]["country"]["en"] = $ret_country; }
	} else {
		$laureateArr["id"] = strval($ret_id);
                if($ret_orgName != NULL) { $laureateArr["orgName"]["en"] = $ret_orgName; }
                if($ret_dob != NULL) { $laureateArr["founded"]["date"] = $ret_dob; }
                if($ret_city != NULL) { $laureateArr["founded"]["place"]["city"]["en"] = $ret_city; }
                if($ret_country != NULL) { $laureateArr["founded"]["place"]["country"]["en"] = $ret_country; }
	}
}

// Nobel prize info
$nobelPrizeQuery = "SELECT awardYear, category, sortOrder, portion, dateAwarded, prizeStatus, motivation, prizeAmount FROM Awarded WHERE id={$laureate_id}";
$prizes = $db->query($nobelPrizeQuery);
while($prize = $prizes->fetch_assoc()) {
	$nobelInfoArr = array();
	$nobelInfoArr["awardYear"] = strval($prize["awardYear"]);
        $nobelInfoArr["category"]["en"] = $prize["category"];
        if($prize["sortOrder"] != NULL) { $nobelInfoArr["sortOrder"] = strval($prize["sortOrder"]); }
        if($prize["portion"] != NULL) { $nobelInfoArr["portion"] = $prize["portion"]; }
        if($prize["dateAwarded"] != NULL) { $nobelInfoArr["dateAwarded"] = $prize["dateAwarded"]; }
        if($prize["prizeStatus"] != NULL) { $nobelInfoArr["prizeStatus"] = $prize["prizeStatus"]; }
        if($prize["motivation"] != NULL) { $nobelInfoArr["motivation"]["en"] = $prize["motivation"]; }
	if($prize["prizeAmount"] != NULL) { $nobelInfoArr["prizeAmount"] = strval($prize["prizeAmount"]); }

	// For each nobel prize, construct an array of affiliations
	$affiliationArr = array();
	$affiliationQuery = "SELECT affiliationName, city, country FROM Affiliation WHERE id={$laureate_id} AND awardYear={$prize["awardYear"]} AND category=\"{$prize["category"]}\"";
        $affiliations = $db->query($affiliationQuery);
	while($affiliation = $affiliations->fetch_assoc()) {
		$affilInfoArr = array();
		$affilInfoArr["name"]["en"] = $affiliation["affiliationName"];
		if($affiliation["city"] != NULL) { $affilInfoArr["city"]["en"] = $affiliation["city"]; }
		if($affiliation["country"] != NULL) { $affilInfoArr["country"]["en"] = $affiliation["country"]; }

		array_push($affiliationArr, $affilInfoArr);
        }
	if(!empty($affiliationArr)) { $nobelInfoArr["affiliations"] = $affiliationArr; }

	array_push($nobelPrizeArr, $nobelInfoArr);
}
if(!empty($nobelPrizeArr)) { $laureateArr["nobelPrizes"] = $nobelPrizeArr; }

// Serialize the array as JSON
echo json_encode($laureateArr);

// Cleanup
$laureate_info->free_result();

$db->close();
exit(0);
?>
