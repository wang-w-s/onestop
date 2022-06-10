function register() {
	username = document.getElementById("username").value;
	pass = document.getElementById("password").value;
	repass = document.getElementById("repeat").value;
	
	if (!valid_user(username) || !valid_pass(pass)) {
		return false;
	}
	
	if (pass !== repass) {
		document.getElementById("warning").innerHTML = "Passwords do not match";
		return false;
	}
	
	return true;
}

function valid_user(str) {
	if (!(str.length >= 6 && str.length <=10)) {
		document.getElementById("warning").innerHTML = "Username must be between 6-10 characters";
		return false;
	}
	
	alphanum = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
	if (str.match(alphanum)) {
		document.getElementById("warning").innerHTML = "Username cannot contain special characters";
		return false;
	}
	
	num = /[0-9]/;
	if (num.test("" + username.charAt(0))){
		document.getElementById("warning").innerHTML = "Username cannot start with a number";
		return false;
	}
	
	return true;
}

function checkpass(str) {
	lower = /[a-z]+/;
	upper = /[A-Z]+/;
	num =  /[0-9]+/;
	
	return lower.test(str) && upper.test(str) && num.test(str);
}

function valid_pass(str) {
	if (!(str.length >= 6 && str.length <=10)) {
		document.getElementById("warning").innerHTML = "Password must be between 6-10 characters";
		return false;
	}
	
	space = /[ ]/;
	if (str.match(space)) {
		document.getElementById("warning").innerHTML = "Password cannot contain spaces";
		return false;
	}
	
	if (!checkpass(str)) {
		document.getElementById("warning").innerHTML = "Password must contain at least one lowercase letter, one uppercase letter, and one number";
		return false;
	}
	
	return true;
}
