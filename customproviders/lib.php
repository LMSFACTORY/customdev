<?php
require_once "../../config.php";
require_once "./classes/customProviders.php";

if (isset($_POST['email'])) {
	$email = $_POST['email'];
	// Create a new user object and check if the user exists in the database.
	$customProvider = new customProviders();
	if ($customProvider->checkProvider($email)) {
		echo json_encode(
			[
				'exists' => true,
				'provider' => $customProvider->getProvider($email),
			]
		);
	} else {
		echo json_encode(['exists' => false]);
	}
} else {
	echo json_encode(['error' => 'Email not provided']);
}