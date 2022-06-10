
var repeatEvent = false;
function toggleRepeat() {
	$("#date").toggle();
	$("#dow").toggle();
	repeatEvent = repeatEvent ? false : true;
	if (repeatEvent) {
		$("#repeat").css("background-color", "#cc0000");
	} else {
		$("#repeat").css("background-color", "#3d5e85");
	}
	
	if ($("#date").is(":hidden")) {
		$("input[name='date']").val("");
	} else {
		$("input[name='dow[]']").prop("checked", false);
	}
}

$("#repeat").hover(function(){
	if (repeatEvent) {
		$("#repeat").css("background-color", "#990000");
	} else {
		$("#repeat").css("background-color", "#224166");
	}
}, function() {
	if (repeatEvent) {
		$("#repeat").css("background-color", "#cc0000");
	} else {
		$("#repeat").css("background-color", "#3d5e85");
	}
});

function checked() {
	checkedDay = $("input[name='dow[]']:checked").length;
	checkedTime = $("input[name='times[]']:checked").length;
	
	dateEmpty = $("input[name='date']").val().length == 0;
	
	if ($("input[name='date']").is(":visible")) {
		if (dateEmpty) {
			document.getElementById("checkbox_note").innerHTML = "You must input a date.";
			return false;
		}
	}

	if ($("input[name='dow[]']").is(":visible")) {
		if(!checkedDay) {
			document.getElementById("checkbox_note").innerHTML = "You must check at least one day.";
			return false;
		}
	}
	
	if (!checkedTime) {
		document.getElementById("checkbox_note").innerHTML = "You must check at least one time.";
		return false;
	}
	return true;
}