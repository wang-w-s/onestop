<?php
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
	
	$weekShown = $_GET['weekShown'];
	$weekDates = generateWeekDates($weekShown);
	
	echo "Week of ".date('m/d/Y', strtotime($weekDates[0]))." - ".date('m/d/Y', strtotime($weekDates[6]));
?>