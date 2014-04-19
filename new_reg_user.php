<?php
	// Start output buffering:
	ob_start();
	// Initialize a session:
	session_start();

	require "db_config.php";
		
	if (isset($_POST['submit'])) { // Handle the form.
		
		// Assume invalid values:
		$email = $password = $firstname = $lastname = $phone = FALSE;
		
		// Check for an email address:
		if (preg_match ('/^[^\W_]+([.-_][^\W_]+)*@[^\W_]+([.-_][^\W_]+)*.[A-Za-z]{2,3}$/', $_POST['email'])) {
			$email = mysql_real_escape_string ($_POST['email']);
		} else {
			$email_error = ": Μη έγκυρο email!";
		}

		// Check for a password and match against the confirmed password:
		if (preg_match('/^[\s\S]{4,128}$/', $_POST['password']) ) {
			if ($_POST['password'] == $_POST['conf_password']) {	
				$password = mysql_real_escape_string ($_POST['password']);
			} 
			else {
				$pass_error = ": Ο Κωδικός σας δεν τεριάζει με τον Κωδικό Επαλήθευσης!";
			}
		} 
		else {
			$pass_error = ": Μη έγκυρος κωδικός!";
		}
		
		// Check for a first name:
		if (preg_match ('/^([a-zA-Z ]*|[Α-Ωαάβγδεέζηήθιίϊΐκλνξοόπρστυύϋΰφχψωώς ]*)?$/', $_POST['first_name'])) {
			$firstname = mysql_real_escape_string ($_POST['first_name']);
		}
		else {
			$fname_error = ": Μη έγκυρο όνομα!";
		}
		
		// Check for a last name:
		if (preg_match ('/^([a-zA-Z ]*|[Α-Ωαάβγδεέζηήθιίϊΐκλνξοόπρστυύϋΰφχψωώ ]*)?$/', $_POST['last_name'])) {
			$lastname = mysql_real_escape_string ($_POST['last_name']);
		} else {
			$lname_error = ": Μη έγκυρο επώνυμο!"; 
		}
		
		//Check for a phone number: ??????
		if(preg_match("/^(2[1-9][0-9]{8}|69[0-9]{8})?$/", $_POST['phone'])) {
			$phone = mysql_real_escape_string ($_POST['phone']);
		} else {
			$phone_error = ": Μη έγκυρος αριθμός!"; 
		}
		
		if ($email && $password && ($fname_error == "") && ($lname_error == "") && ($phone_error == "")) { // If everything's OK...
			// Make sure the email address is available:
			$query = "SELECT user_id FROM users WHERE email= '$email';";
			$result = mysql_query ($query, $dbhandle);
			if (mysql_num_rows($result) != 0) { // The email address is not available.
				$email_error = ": Μη έγκυρο email!";
				$report = "Σφάλμα Εγγραφής: Η διεύθυνση email έχει καταχωρηθεί ήδη!";
			} 
			else { // Available.
				$user_level = "simple";
				// Add the user to the database:
				$query = "INSERT INTO users (email, password, user_level, firstname, lastname, phone) VALUES 
				('$email', '$password', '$user_level', '$firstname', '$lastname', '$phone');";
				$result = mysql_query ($query, $dbhandle);
				if (mysql_affected_rows($dbhandle) != 0) { // If it ran OK.
					$report = "Ευχαριστούμε για την εγγραφή σας στο σύστημα!";
					mysqli_close($dbhandle);
					header('Location: login.php');
				} 
				else { // If it did not run OK.
					$report = "Αποτυχία Εγγραφής: Δοκιμάστε ξανά ή επικοινωνήστε μαζί μας!";
				}
			}
		}
		else { // If one of the data tests failed.
			$report = "Σφάλμα Εγγραφής: Παρακαλώ ελέγξτε τα δεδομένα που εισάγατε!";
		}
	}
	else{
		$email_error = "";
		$pass_error = "";
		$fname_error = "";
		$lname_error = "";
		$phone_error = "";
		$report = "Εισάγετε τα στοιχεία σας στη φόρμα εγγραφής!";
	} // End of the main Submit conditional.
	mysqli_close($dbhandle);
?>
