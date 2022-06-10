var weekShown = 0;

function generateCalendar(weekChg, user, session){
	if (weekChg === "inc") {
		weekShown += 1;
	} else if (weekChg === "dec") {
		weekShown -=1;
	}
	
	var genCalendar = new XMLHttpRequest();
	genCalendar.onreadystatechange = function(){
		if(genCalendar.readyState == 4 && genCalendar.status == 200){
			var ajaxDisplay = document.getElementById('calTable');
			ajaxDisplay.innerHTML = genCalendar.responseText;
			if (weekShown == 0) shadeCal();
		}
	}
	var genCal = "?user=" + user + "&session=" + session + "&weekShown=" + weekShown;
	genCalendar.open("GET", "gencalendar.php" + genCal, true);
	genCalendar.send();
	
	var genWeekRange = new XMLHttpRequest();
	genWeekRange.onreadystatechange = function(){
		if(genWeekRange.readyState == 4 && genWeekRange.status == 200){
			var ajaxDisplay = document.getElementById('weekrange');
			ajaxDisplay.innerHTML = genWeekRange.responseText;
		}
	}
	var genWeekr = "?weekShown=" + weekShown;
	genWeekRange.open("GET", "genweek.php" + genWeekr, true);
	genWeekRange.send();
}

function shadeCal() {
	const now = new Date();

	var daylist = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
	const day = daylist[now.getDay()];
	for (let i = 6; i < 24; i++) {
		cell = day + "-" + getCell(i);
		document.getElementById(cell).style.backgroundColor = "#ebebeb";
	}

	var hour = now.getHours();
	var tableRow = getCell(hour);
	if (hour >= 6) {
		document.getElementById(tableRow).style.backgroundColor = "#ebebeb";
		document.getElementById(day+"-"+tableRow).style.backgroundColor = "#d9d9d9";
	}
}

function getCell(h) {
	prepand = (h >= 12) ? "p" : "a";
	if (h === 0) {
		return "12a";
	} else if (h == 12) {
		return "12p";
	} else if (h > 12) {
		return (h-12) + prepand;
	} else {
		return h + prepand;
	}
}


