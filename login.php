<!DOCTYPE html>

<html lang="en">
	<head>
		<title>Login</title>
		<meta charset="UTF-8">
		<meta name="description" content="allows user to login to registered account">
		<meta name="author" content="William Wang">
		<link href="homepage.css" rel="stylesheet">
	</head>
	
	<body>
		<?php
			session_start();
			
			include 'checkLogin.php';
			if (checkLogin()) {
				header("Location: homepage.php");
			}
			
			$submitted = !empty($_POST['submit']);
			$warning = "";
			if ($submitted) {
				$user = $_POST['username'];
				$pass = $_POST['password'];
				
				$mysqli = connect();
				
				$finduser = $mysqli->prepare("SELECT * FROM users WHERE username=?");
				$finduser->bind_param("s", $user);
				$finduser->execute();
				$arr = $finduser->get_result()->fetch_all(MYSQLI_NUM);
				$finduser->close();
				if (!$arr) {
					$mysqli->close();
					header("Location: login.php?exists=false");
				} else {
					if (strcasecmp($arr[0][0], $user) == 0 && strcmp($arr[0][1], $pass) == 0) {
						$result = login($arr[0][0]);
						if ($result) {
							if (isset($_POST['rememberme'])) {
								setcookie("user", $_SESSION["user"], time()+2592000);
							}
							header("Location: homepage.php");
						} else {
							session_unset();
							session_destroy();
							header("Location: login.php?fail=true");
						}
					} else {
						$mysqli->close();
						session_unset();
						session_destroy();
						header("Location: login.php?user=true");
					}
				}
			}
			
			if (isset($_GET["user"])) {
				$warning = "Incorrect password! Please Try Again";
			} else if (isset($_GET["exists"])) {
				$warning = "User does not exist!";
			} else if (isset($_GET["success"])) {
				$warning = "Successfully registered! Please log in";
			} else if (isset($_GET["fail"])) {
				$warning = "Failed to login";
			} else if (isset($_GET["timeout"])) {
				$warning = "Session timeout! Login again.";
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
			<div class="main">Login to Access Your Calendar</div>
			<form method="post" action="login.php">
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
						<div id="remember"><input type="checkbox" name="rememberme">
						Remember me for 30 days</div>
					</div>
					<?php
						echo '<div id="warning">'.$warning.'</div>';
					?>
					
					<div id="actions">
						<input type="submit" name="submit" value="Login"><br/>
					</div>
					<div id="switchlogreg">
						<a href="register.php">Don't Have an Account?</a>
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