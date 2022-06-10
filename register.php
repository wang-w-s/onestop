<!DOCTYPE html>

<html lang="en">
	<head>
		<title>Register</title>
		<meta charset="UTF-8">
		<meta name="description" content="allows user to register account">
		<meta name="author" content="William Wang">
		<link href="homepage.css" rel="stylesheet">
		<script defer src="register.js"></script>
	</head>
	
	<body>
		<?php
			session_start();
			
			include 'checkLogin.php';
			if (checkLogin()) {
				header("Location: homepage.php");
			}
			
			$submitted = !empty($_POST['submit']);
			$note = "";
			if ($submitted) {
				$user = strtolower($_POST['username']);
				$pass = $_POST['password'];
				
				$mysqli = new mysqli("spring-2021.cs.utexas.edu", "cs329e_bulko_wswang", "more6admit5Bach", "cs329e_bulko_wswang");
				if ($mysqli->connect_errno) {
					die('Connect Error '.$mysqli->connect_errno.': '.$mysqli->connect_error);
				}
				
				$finduser = $mysqli->prepare("SELECT * FROM users WHERE username=?");
				$finduser->bind_param("s", $user);
				$finduser->execute();
				$arr = $finduser->get_result()->fetch_all(MYSQLI_NUM);
				$finduser->close();
				if (!$arr) {
					$sessionkey = "";
					$adduser = $mysqli->prepare("INSERT INTO users VALUES (?, ?, ?)");
					$adduser->bind_param("sss", $user, $pass, $sessionkey);
					$addu = $adduser->execute();
					$adduser->close();
					
					$addtable = sprintf("CREATE TABLE %s (eventid INT(15) PRIMARY KEY AUTO_INCREMENT, edate DATE, dow VARCHAR(3), times VARCHAR(3), title VARCHAR(30), description VARCHAR(300))", $mysqli->real_escape_string($user));
					$addt = $mysqli->query($addtable);
					
					$mysqli->close();
					if ($addu && $addt) {
						header("Location: login.php?success=true");
					} else if ($addu) {
						header("Location: register.php?success=user");
					} else if ($addt) {
						header("Location: register.php?success=table");
					} else {
						header("Location: register.php?success=false");
					}
				} else {
					$mysqli->close();
					header("Location: register.php?warn=true");
				}
			}
			
			if (isset($_GET["warn"])) {
				$note = "Username is taken!";
			} else if (isset($_GET["success"])) {
				if (strcasecmp($_GET["success"], "user") == 0) {
					$note = "Failed to register user!";
				} else if (strcasecmp($_GET["success"], "table") == 0) {
					$note = "Failed to create event table!";
				} else if (strcasecmp($_GET["success"], "false") == 0) {
					$note = "Failed to register completely!";
				}
			}
		?>
		<header>
			<div id="bar">
				<a href="homepage.php">
					<img id="logo" src="logo.png" alt="One Stop Logo">
				</a>
				<nav>
					<a href="homepage.php">
						<div class="nav_l">Home</div>
					</a>
					<a href="about.html">
						<div class="nav_l">About</div>
					</a>
					<a href="self_care.html">
						<div class="nav_l">Self-Care</div>
					</a>
					<a href="study_tips.html">
						<div class="nav_l">Study Tips</div>
					</a>
					<a href="contact_us.html">
						<div class="nav_l">Contact</div>
					</a>
				</nav>
			</div>
		</header>
		
		<div id="login">
			<div class="main">Register for Access to Our Calendar Widget</div>
			<form method="post" action="register.php" onsubmit="return register();">
				<div id="formfields">
					<div class="row">
						<div class="field">
							<label>Username:</label>
						</div>
						<div class="field">
							<input id="username" name="username" type="text" size="20" required>
						</div>
					</div>
					<div class="row">
						<div class="field">
							<label>Password:</label>
						</div>
						<div class="field">
							<input id="password" name="password" type="password" size="20" required>
						</div>
					</div>
					<div class="row">
						<div class="field">
							<label>Repeat Password:</label>
						</div>
						<div class="field">
							<input id="repeat" type="password" size="20" required>
						</div>
					</div>
					<?php
						echo '<div id="warning">'.$note.'</div>';
					?>
					
					<div id="actions">
						<input type="submit" name="submit" value="Register">
						<input type="reset" value="Clear">
					</div>
					<div id="switchlogreg">
						<a href="login.php">Already Have an Account?</a>
					</div>
				</div>
			</form>
		</div>

		<footer>
			<p>Copyright 2021 Christina Peebles, Itssel Sanchez, Joseph Sharpee, William Wang</p>
			<p>Page Last Updated: 04/27/2021</p>
		</footer>
	</body>
</html>