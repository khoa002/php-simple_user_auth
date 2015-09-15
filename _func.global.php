<?php
if (!defined("allow_inc")) die("You can't access this file directly.");
session_start();
$dbc = get_database_connection();
$user = false;
$profile = true;
$page = "page_home.php";
if (isset($_SESSION["user"])) {
	if ($query = $dbc->query("SELECT * FROM `users` LEFT JOIN `profiles` ON `users`.`uid` = `profiles`.`uid` WHERE `users`.`uid` = '{$_SESSION["user"]}'")) {
		if ($row = $query->fetch_assoc()) {
			$user = $row;
			if ($user["uid"] === NULL) { // this means the user profile is not set up
				$user["uid"] = $_SESSION["user"];
				$profile = false;
			}
		}
	}
}
if (isset($_GET["page"])) $page = "page_" . $_GET["page"] . ".php";
if (!file_exists($page)) $page = "page_notfound.php";

// US States
$us_states = array(
	"AL" => "Alabama",
	"AK" => "Alaska",
	"AZ" => "Arizona",
	"AR" => "Arkansas",
	"CA" => "California",
	"CO" => "Colorado",
	"CT" => "Connecticut",
	"DE" => "Delaware",
	"DC" => "District of Columbia",
	"FL" => "Florida",
	"GA" => "Georgia",
	"HI" => "Hawaii",
	"ID" => "Idaho",
	"IL" => "Illinois",
	"IN" => "Indiana",
	"IA" => "Iowa",
	"KS" => "Kansas",
	"KY" => "Kentucky",
	"LA" => "Louisiana",
	"ME" => "Maine",
	"MD" => "Maryland",
	"MA" => "Massachusetts",
	"MI" => "Michigan",
	"MN" => "Minnesota",
	"MS" => "Mississippi",
	"MO" => "Missouri",
	"MT" => "Montana",
	"NE" => "Nebraska",
	"NV" => "Nevada",
	"NH" => "New Hampshire",
	"NJ" => "New Jersey",
	"NM" => "New Mexico",
	"NY" => "New York",
	"NC" => "North Carolina",
	"ND" => "North Dakota",
	"OH" => "Ohio",
	"OK" => "Oklahoma",
	"OR" => "Oregon",
	"PA" => "Pennsylvania",
	"RI" => "Rhode Island",
	"SC" => "South Carolina",
	"SD" => "South Dakota",
	"TN" => "Tennessee",
	"TX" => "Texas",
	"UT" => "Utah",
	"VT" => "Vermont",
	"VA" => "Virginia",
	"WA" => "Washington",
	"WV" => "West Virginia",
	"WI" => "Wisconsin",
	"WY" => "Wyoming",
);

function isPosted() { return ($_SERVER["REQUEST_METHOD"] == "POST") ? true : false; }
function get_database_connection() {
	$server = "localhost";
	$username = "atsuuc5_yzm";
	$password = "JuneHog!@";
	$db_name = "atsuuc5_yzm";
	$mysqli = new mysqli($server, $username, $password, $db_name);
	$mysqli->set_charset("utf8");
	return $mysqli;
}
function generate_salt($max = 15) {
	$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$i = 0;
	$salt = "";
	while ($i < $max) {
		$salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
		$i++;
	}
	return $salt;
}
?>