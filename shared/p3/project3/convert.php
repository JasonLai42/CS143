<?php 
$laureates_path = "Laureates.del";
$awarded_path = "Awarded.del";
$affiliations_path = "Affiliations.del";

if(file_exists($laureates_path)) {
	unlink($laureates_path);
} if(file_exists($awarded_path)) {
	unlink($awarded_path);
} if(file_exists($affiliations_path)) {
	unlink($affiliations_path);
}

$laureates_file = fopen($laureates_path, "w");
$awarded_file = fopen($awarded_path, "w");
$affiliations_file = fopen($affiliations_path, "w");

// Get JSON file content as JSON string and convert to PHP object
$strFileContents = file_get_contents("/home/cs143/data/nobel-laureates.json");
$laurObj = json_decode($strFileContents, true);

// Get array of laureates
$laurArray = $laurObj["laureates"];

// Construct tuples in string form
$laureateStr = "";
$awardedStr = "";
$affiliationStr = "";

// Map to find duplicates
$laureateDict = array();
$awardedDict = array();
$affiliationDict = array();

$i = 0;
foreach($laurArray as $laureate) {
	// People laureates
	if(!array_key_exists("orgName", $laureate)) {
		$laureateStr = $laureate["id"];
                if($laureate["givenName"]["en"] ?? NULL) {
                        $laureateStr .= ",\"{$laureate["givenName"]["en"]}\"";
                } else {
                        $laureateStr .= ",NULL";
                } if($laureate["familyName"]["en"] ?? NULL) {
                        $laureateStr .= ",\"{$laureate["familyName"]["en"]}\"";
                } else {
			$laureateStr .= ",NULL";
                } if($laureate["gender"] ?? NULL) {
                        $laureateStr .= ",\"{$laureate["gender"]}\"";
                } else {
			$laureateStr .= ",NULL";
                } 
                $laureateStr .= ",NULL";
                if($laureate["birth"]["date"] ?? NULL) {
                        $laureateStr .= ",\"{$laureate["birth"]["date"]}\"";
                } else {
			$laureateStr .= ",NULL";
                } if($laureate["birth"]["place"]["city"]["en"] ?? NULL) {
                        $laureateStr .= ",\"{$laureate["birth"]["place"]["city"]["en"]}\"";
                } else {
			$laureateStr .= ",NULL";
                } if($laureate["birth"]["place"]["country"]["en"] ?? NULL) {
                        $laureateStr .= ",\"{$laureate["birth"]["place"]["country"]["en"]}\"";
                } else {
			$laureateStr .= ",NULL";
                }
                $laureateStr .= ",\\N\n";
	}
	// Organization laureates
	else {
		$laureateStr = $laureate["id"]
				. ",NULL"
				. ",NULL"
				. ",NULL";
		if($laureate["orgName"]["en"] ?? NULL) {
			$laureateStr .= ",\"{$laureate["orgName"]["en"]}\"";
		} else {
			$laureateStr .= ",NULL";
		} if($laureate["founded"]["date"] ?? NULL) {
			$laureateStr .= ",\"{$laureate["founded"]["date"]}\"";
		} else {
			$laureateStr .= ",NULL";
		} if($laureate["founded"]["place"]["city"]["en"] ?? NULL) {
			$laureateStr .= ",\"{$laureate["founded"]["place"]["city"]["en"]}\"";
		} else {
			$laureateStr .= ",NULL";
		} if($laureate["founded"]["place"]["country"]["en"] ?? NULL) {
			$laureateStr .= ",\"{$laureate["founded"]["place"]["country"]["en"]}\"";
		} else {
			$laureateStr .= ",NULL";
		}
		$laureateStr .= ",\\N\n";
	}

	// Check for any duplicate laureate primary keys
	if(!array_key_exists($laureate["id"], $laureateDict)) {
		fwrite($laureates_file, $laureateStr);
		$laureateDict[$laureate["id"]] = $laureate["id"];
	}

	$awardedTemp = $laureate["id"];
        $affiliationTemp1 = $laureate["id"];

	// Nobel Prizes
	foreach($laureate["nobelPrizes"] as $nobelPrize) {
		$awardedKey = $awardedTemp
				. ",{$nobelPrize["awardYear"]}"
				. ",\"{$nobelPrize["category"]["en"]}\"";

		$awardedStr = $awardedKey;
		if($nobelPrize["sortOrder"] ?? NULL) {
			$awardedStr .= ",{$nobelPrize["sortOrder"]}";
		} else {
			$awardedStr .= ",NULL";
		} if($nobelPrize["portion"] ?? NULL) {
			$awardedStr .= ",\"{$nobelPrize["portion"]}\"";
		} else {
			$awardedStr .= ",NULL";
		} if($nobelPrize["dateAwarded"] ?? NULL) {
                        $awardedStr .= ",\"{$nobelPrize["dateAwarded"]}\"";
                } else {
                        $awardedStr .= ",NULL";
		} if($nobelPrize["prizeStatus"] ?? NULL) {
			$awardedStr .= ",\"{$nobelPrize["prizeStatus"]}\"";
		} else {
			$awardedStr .= ",NULL";
		} if($nobelPrize["motivation"]["en"] ?? NULL) {
			$awardedStr .= ",\"{$nobelPrize["motivation"]["en"]}\"";
		} else {
			$awardedStr .= ",NULL";
		} if($nobelPrize["prizeAmount"] ?? NULL) {
                        $awardedStr .= ",{$nobelPrize["prizeAmount"]}";
                } else {
                        $awardedStr .= ",NULL";
		}
		$awardedStr .= ",\\N\n";

		// Check for any duplicate awarded keys
		if(!array_key_exists($awardedKey, $awardedDict)) {
			fwrite($awarded_file, $awardedStr);
			$awardedDict[$awardedKey] = $awardedKey;
		}

		if($nobelPrize["awardYear"] ?? NULL) {
			$affiliationTemp2 = ",{$nobelPrize["awardYear"]}";
		} else {
			$affiliationTemp2 = ",NULL";
		} if($nobelPrize["category"]["en"] ?? NULL) {
			$affiliationTemp2 .= ",\"{$nobelPrize["category"]["en"]}\"";
		} else {
			$affiliationTemp2 .= ",NULL";
		}

		if(array_key_exists("affiliations", $nobelPrize)) {
			foreach($nobelPrize["affiliations"] as $affiliation) {
				$affiliationKey = $affiliationTemp1
						. $affiliationTemp2
						. ",\"{$affiliation["name"]["en"]}\"";

				$affiliationStr = $affiliationKey;
				if($affiliation["city"]["en"] ?? NULL) {
                                        $affiliationStr .= ",\"{$affiliation["city"]["en"]}\"";
                                } else {
                                        $affiliationStr .= ",NULL";
                                } if($affiliation["country"]["en"] ?? NULL) {
                                        $affiliationStr .= ",\"{$affiliation["country"]["en"]}\"";
                                } else {
                                        $affiliationStr .= ",NULL";
                                }
				$affiliationStr .= ",\\N\n";

				// Entire row is a key, but still check for duplicates
				if(!array_key_exists($affiliationKey, $affiliationDict)) {
					fwrite($affiliations_file, $affiliationStr);
					$affiliationDict[$affiliationKey] = $affiliationKey;
				}

			}
		}
	}
}

fclose($laureates_file);
fclose($awarded_file);
fclose($affiliations_file);
?>
