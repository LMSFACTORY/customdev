<?php
require_once "../../config.php";


if (isset($_GET)) {
	global $DB;
	$email = $_GET['email'];
	// Create a new user object and check if the user exists in the database.
	$user = $DB->get_record('user', ['email' => $email]);
	if ($user) {
		echo json_encode(
			[
				'exists' => true,
				'provider' => $user
			]);
	} else {
		echo json_encode(['exists' => false]);
	}
} else {
	echo json_encode(['error' => 'Email not provided']);
}
