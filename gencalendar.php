<?php
	function connect() {
		$mysqli = new mysqli("spring-2021.cs.utexas.edu", "cs329e_bulko_wswang", "more6admit5Bach", "cs329e_bulko_wswang");
		if ($mysqli->connect_errno) {
			die('Connect Error '.$mysqli->connect_errno.': '.$mysqli->connect_error);
		}
		
		return $mysqli;
	}

	function getEventString($date, $dow, $time, $u) {
		$mysqli = connect();
		
		$eventarr = [];
		if ($date == "") {
			$eventget = $mysqli->prepare("SELECT * FROM $u WHERE dow=? AND times=?");
			$eventget->bind_param("ss", $dow, $time);
			$eventget->execute();
			$eventres = $eventget->get_result();
			if ($eventres) {
				$eventarr = $eventres->fetch_all(MYSQLI_NUM);
			}
			$eventget->close();
		} else {
			$eventget = $mysqli->prepare("SELECT * FROM $u WHERE edate=? AND times=?");
			$eventget->bind_param("ss", $date, $time);
			$eventget->execute();
			$eventres = $eventget->get_result();
			if ($eventres) {
				$eventarr = $eventres->fetch_all(MYSQLI_NUM);
			}
			$eventget->close();
		}
		$mysqli->close();
		
		$return = "";
		for ($i = 0; $i < count($eventarr); $i++) {
			$eid = $eventarr[$i][0];
			$etitle = $eventarr[$i][4];
			$edescrip = $eventarr[$i][5];
			$buttid = "event";
			if ($date == "") {
				$buttid = "eventr";
			}
			$button = <<<BUT
						<button class="$buttid" onclick="$('#$eid').show();">$etitle</button>
						<div class="eventdescrip" id="$eid">
							<div class="sub">$etitle</div>
							<p>$edescrip</p>
							<div class="eventcontrol">
								<button class="e_action" onclick="$('#$eid').hide();">Close</button>
								<button class="e_action" id="delete" onclick="location.href='homepage.php?del=$eid';">Delete</button>
							</div>
						</div>\n
BUT;
			$return .= $button;
		}
		
		return $return;
	}
	
	function generateWeekDates($week) {
		$dates = [];
		
		$day = strtotime("last sunday");
		$day = date('w', $day) == date('w') ? $day+7*86400+($week*7*86400) : $day+($week*7*86400);
		$dates[] = date('Y-m-d', $day);
		
		for ($i = 0; $i < 6; $i++) {
			$day = strtotime(date('Y-m-d', $day)." +1 day");
			$dates[] = date('Y-m-d', $day);
		}
		
		return $dates;
	}

	function checkLogin($u, $s) {
		$mysqli = connect();
		
		$findactive = $mysqli->prepare("SELECT * FROM users WHERE username=? AND session=?");
		$findactive->bind_param("ss", $u, $s);
		$findactive->execute();
		$arr = $findactive->get_result()->fetch_all(MYSQLI_NUM);
		$findactive->close();
		$mysqli->close();
		
		$loggedin = true;
		if (count($arr) == 0) $loggedin = false;
		return $loggedin;
	}
	
	$user = $_GET['user'];
	$weekShown = $_GET['weekShown'];
	$sessid = $_GET['session'];
	
	$loggedin = checkLogin($user, $sessid);
	$weekDates = generateWeekDates($weekShown);
	
	$printTable = <<<TABLE
			<table id="calendar">
				<tbody>
				<tr>
					<th style="width: 50px;"></th>
					<th>Sun.</th>
					<th>Mon.</th>
					<th>Tues.</th>
					<th>Wed.</th>
					<th>Thurs.</th>
					<th>Fri.</th>
					<th>Sat.</th>
				</tr>\n
TABLE;

	$dow = array("sun", "mon", "tue", "wed", "thu", "fri", "sat");
	$times = array("6a", "7a", "8a", "9a", "10a", "11a", "12p", "1p", "2p", "3p", "4p", "5p", "6p", "7p", "8p", "9p", "10p", "11p");
	$timestring = array("6 a.m.", "7 a.m.", "8 a.m.", "9 a.m.", "10 a.m.", "11 a.m.", "12 p.m.", "1 p.m.", "2 p.m.", "3 p.m.", "4 p.m.", "5 p.m.", "6 p.m.", "7 p.m.", "8 p.m.", "9 p.m.", "10 p.m.", "11 p.m.");
	for ($i = 0; $i < count($times); $i++) {
		$t = $times[$i];
		$ts = $timestring[$i];
		$rbegin = <<<ROW
				<tr id="$t">
					<td class="time" style="vertical-align: inherit;">$ts</td>\n
ROW;
		$printTable .= $rbegin;
		for ($j = 0; $j < count($dow); $j++) {
			$d = $dow[$j];
			$repeatEvents = "";
			$dateEvents = "";
			if ($loggedin) {
				$repeatEvents = getEventString("", $d, $t, $user);
				$dateEvents = getEventString($weekDates[$j], $d, $t, $user);
			}
			$cell = <<<CELL
					<td id="$d-$t">
$repeatEvents
$dateEvents
					</td>\n
CELL;
			$printTable .= $cell;
		}
		$rfin = <<<ROW
				</tr>\n
ROW;
		$printTable .= $rfin;
	}
	$tablefin = <<<FIN
			</tbody>
			</table>\n
FIN;
	print $printTable.$tablefin;
?>
	