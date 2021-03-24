<!-- 
This is the home page for Final Project, Quotation Service. 
It is a PHP file because later on you will add PHP code to this file.

File name quotes.php 
    
Authors: Rick Mercer and Sooyoung Moon
-->

<!DOCTYPE html>
<html>

<head>
	<title>Quotation Service</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body onload="showQuotes()">

	<h1>Quotation Service</h1>


	<?php
	session_start();

	if (isset($_SESSION['user'])) {
		echo '
	<div id="navi">
	<form action="controller.php" method="post">
	<button name="logout" value="login">Logout</button>
	</form> 
	<form action="addQuote.php" method="post">
	<button name="addQuote" value="addQuote">Add Quote</button>
	</form> 
	</div>';
	} else {
		echo '
		<div id="navi">
		<form action="register.php" method="post">
		<button name="register" value="register">Register</button>
		</form> 
		<form action="login.php" method="post">
		<button name="login" value="login">Login</button>
		</form>
		</div>';
	}


	if (isset($_SESSION['user'])) {
		echo '<div id="welcome-text" >Hello ' . $_SESSION['user'] . "<br></div>";
	}
	?>

	<div id="quotes"></div>

	<script>
		var element = document.getElementById("quotes");

		function showQuotes() {
			// TODO 5: 
			// Complete this function using an AJAX call to controller.php
			// You will need query parameter todo=getQuotes.
			// Echo back one big string to here that has all styled quotations.
			// Write all of the complex code to layout the array of quotes 
			// inside function getQuotesAsHTML inside controller.php.

			var ajax = new XMLHttpRequest();
			ajax.open('GET', 'controller.php?todo=getQuotes', true);
			ajax.send();
			ajax.onreadystatechange = function() {
				console.log(ajax.readyState);
				if (ajax.readyState == 4 && ajax.status == 200) {
					element.innerHTML = ajax.responseText;
				}
			}

		} // End function showQuotes
	</script>



</body>

</html>