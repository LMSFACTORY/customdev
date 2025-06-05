<?php
/**
 * Custom form for moodle
 * This page is the check user page of the custom form. It checks if the user exists in the database or not which is called be the ajax request from the checkemail.php file.
 */
require_once('./classes/User.php');

if (isset($_POST['email'])) {
	$email = $_POST['email'];
	// Create a new user object and check if the user exists in the database.
	$user = new User();
	if ($user->findUserByEmail($email)) {
		echo json_encode(['exists' => true]);
	} else {
		echo json_encode(['exists' => false]);
	}
} else {
	echo json_encode(['error' => 'Email not provided']);
}
