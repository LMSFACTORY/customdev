<?php

require_once __DIR__ . '/../../config.php';
require_once './classes/customProviders.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['delete_provider']) && $_POST['delete_provider'] == true) {
		if (!empty($_POST['email']) && !empty($_POST['id'])) {
			$email = $_POST['email'];
			$id = $_POST['id'];

			$customProvider = new customProviders();
			$customProvider->deleteProvider($email);

			echo json_encode([
				'success' => true,
				'message' => "Le fournisseur avec l'email $email a été supprimé avec succès."
			]);
		} else {
			echo json_encode([
				'success' => false,
				'error' => 'Email ou ID manquant.'
			]);
		}
	} else {
		echo json_encode([
			'success' => false,
			'error' => 'Requête invalide.'
		]);
	}
}