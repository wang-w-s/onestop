<?php
	function connect() {
		$mysqli = new mysqli("spring-2021.cs.utexas.edu", "cs329e_bulko_wswang", "more6admit5Bach", "cs329e_bulko_wswang");
		if ($mysqli->connect_errno) {
			die('Connect Error '.$mysqli->connect_errno.': '.$mysqli->connect_error);
		}
		
		return $mysqli;
	}

	function logout() {
		setcookie("user", "", time()-3600);
		$s = session_id();
		$user = $_SESSION["user"];
		$news = "";
		
		$mysqli = connect();
		
		$findactive = $mysqli->prepare("UPDATE users SET session=? WHERE username=? AND session=?");
		$findactive->bind_param("sss", $news, $user, $s);
		$result = $findactive->execute();
		$findactive->close();
		$mysqli->close();
		session_unset();
		session_destroy();
	}

	function checkLogin() {		
		if (!isset($_SESSION["user"])) {
			return false;
		}
		
		$s = session_id();
		$user = $_SESSION["user"];
		
		$mysqli = connect();
		
		$findactive = $mysqli->prepare("SELECT * FROM users WHERE username=? AND session=?");
		$findactive->bind_param("ss", $user, $s);
		$findactive->execute();
		$arr = $findactive->get_result()->fetch_all(MYSQLI_NUM);
		$findactive->close();
		$mysqli->close();
		
		$loggedin = true;
		if (count($arr) == 0) $loggedin = false;
		return $loggedin;
	}
	
	function login($u) {
		$s = session_id();
		$_SESSION['user'] = $u;
		
		$mysqli = connect();
		
		$logactive = $mysqli->prepare("UPDATE users SET session=? WHERE username=?");
		$logactive->bind_param("ss", $s, $u);
		$result = $logactive->execute();
		$logactive->close();
		$mysqli->close();
		
		return $result;
	}
	
	function insertEvent($date, $dow, $time, $title, $descrip) {
		$user = $_SESSION['user'];
		
		$mysqli = connect();
		
		$eventcomm = $mysqli->prepare("INSERT INTO $user (edate, dow, times, title, description) VALUES (?, ?, ?, ?, ?)");
		$eventcomm->bind_param("sssss", $date, $dow, $time, $title, $descrip);
		$eventin = $eventcomm->execute();
		$eventcomm->close();
		$mysqli->close();
		
		if (!$eventin) exit("failed to insert");
	}
	
	function deleteEvent($eventid) {
		$user = $_SESSION['user'];
		
		$mysqli = connect();
		
		$delevent = $mysqli->prepare("DELETE FROM $user WHERE eventid=?");
		$delevent->bind_param("s", $eventid);
		$result = $delevent->execute();
		$delevent->close();
		$mysqli->close();
	}
?>