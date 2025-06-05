<?php
/**
 * User class
 * This class is responsible for handling the user registration and updating the user profile.
 * It also contains the error handling function.
 * @package customforms
 */

// Include the necessary files and classes
require_once('../../config.php');
require_once('./db/Database.php');

class User
{
	// Database connection and table name
	private $db;
	private string $tablename;

	/**
	 * Constructor function to initialize the database connection and table name.
	 */
	public function __construct()
	{
		$this->db = new Database;
		$this->tablename = 'users_opco';
	}

	/**
	 * A private function to validate if certain fields are numeric or not.
	 * @param $str
	 * @return bool|int
	 */
	private function isNumeric($str): bool|int
	{
		return preg_match('/^[0-9]+$/', $str);
	}

	/**
	 * A private function to validate the phone number.
	 * This function will take the phone number as a parameter and check if the phone number is valid or not and it
	 * will check for the parameters like the phone number should only contain numbers, spaces, and an optional + sign at the start.
	 * @param $phoneNumber
	 * @return bool
	 */
	private function validatePhoneNumber($phoneNumber): bool
	{
		// Regular expression pattern to allow only numbers, spaces, and optional + sign at the start
		$pattern = '/^\+?[0-9 ]+$/';
		// Check if the phone number matches the pattern
		if (preg_match($pattern, $phoneNumber)) {
			return true; // Valid phone number
		} else {
			return false; // Invalid phone number
		}
	}

	/**
	 * A public function to check if the user exists in the database.
	 * @param $email
	 * @return bool
	 */
	public function findUserByEmail($email): bool
	{
		$this->db->query('SELECT * FROM users_opco WHERE email = :email');
		$this->db->bind(':email', $email);
		$row = $this->db->single();
		// Check row
		if ($this->db->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * A private function to handle the errors in the form fields.
	 * This function takes the data array and checks for the errors in the every form fields. If there are any errors,
	 * we set the error message in the data array and return the data array with all the data entered by the user
	 * which will be used to prefill the form fields.
	 * @param $data
	 * @return mixed
	 */
	private function errorHandler($data): mixed
	{
		// Check for civilite
		if (empty($data['civilite'])) {
			$data['civilite_err'] = 'Veuillez sélectionner l\'une des options suivantes.';
		}
		// Check for firstname
		if (empty($data['firstname'])) {
			$data['firstname_err'] = 'Veuillez saisir un prénom valide.';
		}
		// Check for lastname
		if (empty($data['lastname'])) {
			$data['lastname_err'] = 'Veuillez saisir un nom valide.';
		}
		// Check for email
		if (empty($data['email'])) {
			$data['email_err'] = 'Veuillez saisir un mail valide.';
		}

		// Check for address
		if (empty($data['address'])) {
			$data['address_err'] = 'Veuillez compléter l\'adresse.';
		}
		// Check for postal code
		if (empty($data['postalcode'])) {
			$data['postalcode_err'] = 'Veuillez entrer un code postal valide.';
		}
//			if (!preg_match('/^\d{5}$/', $data['postalcode'])) {
//				$data['postalcode_err'] = 'Le code postal ne doit contenir que 5 chiffres.';
//			}
		// Check for region
		if (empty($data['region']) || $data['region'] == '0' || $data['region'] == 0) {
			$data['region_err'] = 'Veuillez sélectionner une région.';
		}
		// Check for offer_formation
		if (empty($data['offer_formation']) || $data['offer_formation'] == '0' || $data['offer_formation'] == 0) {
			$data['offer_formation_err'] = "Veuillez cocher la case suivante.";
		}
		// Check for telephone
		if (empty($data['telephone'])) {
			$data['telephone_err'] = 'Veuillez saisir un numéro de téléphone valide.';
		} else if (!$this->validatePhoneNumber($data['telephone'])) {
			$data['telephone_err'] = 'Le numéro de téléphone doit contenir des chiffres uniquement.';
		}
		// Check for city
		if (empty($data['city'])) {
			$data['city_err'] = 'Veuillez saisir une ville valide.';
		}
		// Check for country
		if (empty($data['country'])) {
			$data['country_err'] = 'Veuillez saisir un pays valide.';
		}
		// Check for all the fields if the account type is Professionnel
		if ($data['account_type'] == 'Professionnel') {
			// Check for raison
			if (empty($data['raison'])) {
				$data['raison_err'] = 'Veuillez saisir une raison sociale.';
			}
			// Check for siret
			if (empty($data['siret'])) {
				$data['siret_err'] = 'Veuillez saisir un numéro SIRET.';
			} else {
				if (strlen($data['siret']) !== 14) {
					$data['siret_err'] = 'Le numéro SIRET doit contenir 14 chiffres';
				}
				if ($this->isNumeric($data['siret']) == 0 || !$this->isNumeric($data['siret'])) {
					$data['siret_err'] = 'Le numéro SIRET doit contenir des chiffres uniquement.';
				}
			}
			// Check for function
			if (empty($data['function']) || $data['function'] == '0' || $data['function'] == 0) {
				$data['function_err'] = 'Veuillez saisir une fonction.';
			}
			// Check for activity
			if (empty($data['activity']) || $data['activity'] == '0' || $data['activity'] == 0) {
				$data['activity_err'] = 'Veuillez saisir une activité.';
			}
			if ($data['is_checked'] !== 'checked') {
				// Check for raison_facture
				if (empty($data['raison_facture'])) {
					$data['raison_facture_err'] = 'Veuillez saisir une raison sociale de facturation.';
				}
				// Check for address_facture
				if (empty($data['address_facture'])) {
					$data['address_facture_err'] = 'Veuillez compléter l\'adresse de facturation.';
				}
				// Check for postalcode_facture
				if (empty($data['postalcode_facture'])) {
					$data['postalcode_facture_err'] = 'Veuillez entrer un code postal de facturation valide.';
				}
				// Check for city_facture
				if (empty($data['city_facture'])) {
					$data['city_facture_err'] = 'Veuillez saisir une ville de facturation valide.';
				}
				// Check for country_facture
				if (empty($data['country_facture'])) {
					$data['country_facture_err'] = 'Veuillez saisir un pays de facturation valide.';
				}
				// Check for if the user has selected the same address for facturation if not, we will return it as a
				// variable to be used in the form to display the facturation fields and prefill the fields.
				$data['is_checked'] = 'not_checked';
			}
		}
		//check the $data array keys if there is an _err key if yes that means there is an error. so we set isErrors to true.
		//Otherwise, we would have to add the isErrors variable to all the conditions above.
		$data['isErrors'] = (bool)array_filter($data, function ($key) {
			return str_contains($key, '_err');
		}, ARRAY_FILTER_USE_KEY);
		return $data;
	}

	/**
	 * A public function to get the user by email.
	 * @param $email
	 * @return mixed
	 */
	public function get_user($email): mixed
	{
		$this->db->query('SELECT * FROM users_opco WHERE email = :email');
		$this->db->bind(':email', $email);
		$row = $this->db->single();
		if ($this->db->rowCount() > 0) {
			return $row;
		} else {
			return `L'utilisateur n'exist pas dans la base de donneés.`;
		}
	}

	/**
	 * A public function to register the user. This function takes the data array as a parameter and returns the results.
	 * This function will be called when the user submits the registration form and if everything is successful, we
	 * will insert the user into the database.
	 * @param array $data
	 * @return mixed
	 */
	public function register(array $data): mixed
	{
		$data['email'] = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
		$data['email'] = strtolower($data['email']);
		$data['remise_eligible'] = 'Non';
		$results = [];
		// Checking if the factorization address and personal address is the same.
		if ($data['is_checked'] == 'checked') {
			$data['address_facture'] = $data['address'];
			$data['postalcode_facture'] = $data['postalcode'];
			$data['city_facture'] = $data['city'];
			$data['country_facture'] = $data['country'];
			$data['address_comp_facture'] = $data['address_comp'];
			$data['raison_facture'] = $data['raison'];
		}
		// Check the errors in the data array. Passing the data array to the errorHandler function.
		$data = $this->errorHandler($data);
		// If there are no errors, we insert the user into the database.
		if (!$data['isErrors']) {
			// If the account type is Particulier, we set the nom_etab to the email and the rh_inv and rh_delegue to Non.
			if ($data['account_type'] == 'Particulier') {
				$data['nom_etab'] = $data['email'];
				$data['rh_inv'] = 'Non';
				$data['rh_delegue'] = 'Non';
			}
			// If the account type is Professionnel, we set the nom_etab to the siret and the rh_inv and rh_delegue to Oui.
			if ($data['account_type'] == 'Professionnel') {
				$data['nom_etab'] = $data['siret'];
				$data['rh_inv'] = 'Oui';
				$data['rh_delegue'] = 'Oui';
			}
			$data['branche_app'] = 'E-commerce';
			if ($this->insertUserIntoDB($data)) {
				$results['success'] = true;
			} else {
				$results['success'] = false;
			}
		} else {
			$errors = $data;
			$results['errors'] = $errors;
		}
		return $results;
	}

	/**
	 * A public function to update the stagiaires that has been created by the user with the account_type of Professionnel.
	 * This function takes the data array as a parameter and returns the results.
	 * This function is usually called after the user professional has updated their profile and If everything is
	 * successful,we will update the stagiaires that has been created by the user.
	 * @param array $data
	 * @return bool
	 */

	public function updateSubs(array $data): bool
	{
		global $USER;
		$isChecked = isset($_POST['toggleFields']) ? 'checked' : 'not_checked';
		$useremail = $data['email'];
		$isChecked = isset($data['remise']) ? 'checked' : 'not_checked';
		$raison = $data['raison'];
		$activity = $data['activity'];
		$telephone = $data['telephone'];
		$address = $data['address'];
		$postalcode = $data['postalcode'];
		$city = $data['city'];
		$country = $data['country'];
		$region = $data['region'];
		$address_comp = $data['address_comp'];
		$raison_facture = $data['raison_facture'];
		$address_facture = $data['address_facture'];
		$postalcode_facture = $data['postalcode_facture'];
		$city_facture = $data['city_facture'];
		$country_facture = $data['country_facture'];
		$address_comp_facture = $data['address_comp_facture'];
		$offer_formation = $data['offer_formation'];
		$ref_email = $data['email'];
		$ref_name = $data['firstname'];
		$ref_lastname = $data['lastname'];

		//Updating all the users with the current user as their reference.
		$sql = "UPDATE users_opco SET raison = '$raison',  activity = '$activity', telephone = '$telephone',  address = '$address',  postalcode = '$postalcode',  city = '$city',  country = '$country',  region = '$region',  address_comp = '$address_comp',  raison_facture = '$raison_facture',  address_facture = '$address_facture',  postalcode_facture = '$postalcode_facture',  city_facture = '$city_facture',  country_facture = '$country_facture',  address_comp_facture = '$address_comp_facture',  offer_formation = '$offer_formation', prenom_refclient = '$ref_name', nom_refclient = '$ref_lastname', email_refclient = '$ref_email' WHERE ref_id = '$USER->id'";
		$this->db->query($sql);
		if ($this->db->execute()) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * A public function to update the user profile. This function takes the data array as a parameter and returns the results.
	 * This functions takes data array as a parameter and returns the results whether
	 * @param array $data
	 * @return bool
	 */
	public function update($data): mixed
	{
		$results = [];
		// SQL to update user profile
		$useremail = strtolower($data['email']);

		// Check if the factorization address and personal address is the same.
		$data = $this->sameAddress($data);

		// Check the errors in the data array.
		$data = $this->errorHandler($data);

		// If there are no errors, we update the user profile.
		$email = strtolower($data['email']);
		$civilite = $data['civilite'];
		$firstname = $data['firstname'];
		$lastname = $data['lastname'];
		$function = $data['function'];
		$raison = $data['raison'];
		$activity = $data['activity'];
		$telephone = $data['telephone'];
		$address = $data['address'];
		$postalcode = $data['postalcode'];
		$city = $data['city'];
		$country = $data['country'];
		$region = $data['region'];
		$address_comp = $data['address_comp'];
		$raison_facture = $data['raison_facture'];
		$address_facture = $data['address_facture'];
		$postalcode_facture = $data['postalcode_facture'];
		$city_facture = $data['city_facture'];
		$country_facture = $data['country_facture'];
		$address_comp_facture = $data['address_comp_facture'];
		$offer_formation = $data['offer_formation'];

		$sql = "UPDATE users_opco SET  civilite = '$civilite', firstname = '$firstname',  lastname = '$lastname',  fonction = '$function',  raison = '$raison',  activity = '$activity',address = '$address',  postalcode = '$postalcode',  city = '$city',  country = '$country',  region = '$region',  address_comp = '$address_comp', telephone = '$telephone', raison_facture = '$raison_facture',  address_facture = '$address_facture',  postalcode_facture = '$postalcode_facture',  city_facture = '$city_facture',  country_facture = '$country_facture',  address_comp_facture = '$address_comp_facture', offer_formation = '$offer_formation'  WHERE email = '$useremail'";
		// If there are no errors, we update the user profile.
		if (!$data['isErrors']) {
			$this->db->query($sql);
			if ($this->db->execute()) {
				$results['success'] = $this->updateSubs($data);
			} else {
				$results['exec_error'] = 'Database execution failed';
			}
		} else {
			$results['errors'] = $data;
		}
		return $results;
	}

	/**
	 * A private function to check if the facturation address is the same as the personal address.
	 * If the facturation address is the same as the personal address, we set the facturation address to the personal address.
	 * If the facturation address is not the same as the personal address, we set the facturation address to the
	 * values given in the form.
	 * @param $data
	 * @return array
	 */
	private function sameAddress($data): array
	{
		$isChecked = isset($data['toggleFields']) ? 'checked' : 'not_checked';
		if ($isChecked === 'not_checked') {
			$address_facture = [
				'address_facture' => trim($data['address_facture']),
				'postalcode_facture' => trim($data['postalcode_facture']),
				'city_facture' => trim($data['city_facture']),
				'country_facture' => trim($data['country_facture']),
				'address_comp_facture' => trim($data['address_comp_facture']),
				'raison_facture' => trim($data['raison_facture'])
			];
		} else {
			$address_facture = [
				'address_facture' => trim($data['address']),
				'postalcode_facture' => trim($data['postalcode']),
				'city_facture' => trim($data['city']),
				'country_facture' => trim($data['country']),
				'address_comp_facture' => trim($data['address_comp']),
				'raison_facture' => trim($data['raison'])
			];
			$data['is_sameAddress'] = true;
		}
		return array_merge($data, $address_facture);
	}

	/**
	 * A public function to insert the user into the database.
	 * This function takes the data array as a parameter after all the processing like checking for errors and is
	 * then added to the database.
	 * This function returns the boolean value based on the database execution.
	 * @param array $data
	 * @return bool
	 */
	public function insertUserIntoDB(array $data): bool
	{
		if ($data['account_type'] == "Particulier") {
			$sql = "INSERT INTO users_opco (civilite, username, firstname, lastname, email, address, postalcode, city, country , address_comp, offer_formation, info_formation, account_type, nom_etab, rh_inv, rh_delegue, branche_app, address_facture, postalcode_facture, city_facture, country_facture, address_comp_facture, rgpd, region, telephone, remise_eligible)
        VALUES (:civilite, :username, :firstname, :lastname, :email, :address, :postalcode, :city, :country , :address_comp, :offer_formation, :info_formation, :account_type, :nom_etab, :rh_inv, :rh_delegue, :branche_app, :address_facture, :postalcode_facture, :city_facture, :country_facture,:address_comp_facture,:rgpd, :region, :telephone, :remise_eligible)";
		} else if ($data['account_type'] == 'Professionnel') {
			$sql = "INSERT INTO users_opco (civilite, username, firstname, lastname, email, address, postalcode, city, country , address_comp, offer_formation, info_formation, account_type, nom_etab, activity, fonction, raison,rh_inv, rh_delegue, branche_app, address_facture,postalcode_facture, city_facture, country_facture, address_comp_facture, rgpd,conv_remise, region, raison_facture,telephone, remise_eligible)
        VALUES (:civilite, :username, :firstname, :lastname, :email, :address, :postalcode, :city, :country , :address_comp, :offer_formation, :info_formation, :account_type, :nom_etab, :activity, :fonction, :raison, :rh_inv, :rh_delegue, :branche_app, :address_facture, :postalcode_facture, :city_facture, :country_facture, :address_comp_facture, :rgpd,:conv_remise, :region,:raison_facture,:telephone, :remise_eligible)";
		}
		$this->db->query($sql);

		$this->db->bind(':civilite', $data['civilite']);
		$this->db->bind(':username', $data['email']);
		// $this->db->bind(':password', $data['password']);
		$this->db->bind(':firstname', $data['firstname']);
		$this->db->bind(':lastname', $data['lastname']);
		$this->db->bind(':email', $data['email']);
		$this->db->bind(':address', $data['address']);
		$this->db->bind(':postalcode', $data['postalcode']);
		$this->db->bind(':city', $data['city']);
		$this->db->bind(':country', $data['country']);
		$this->db->bind(':address_comp', $data['address_comp']);
		$this->db->bind(':offer_formation', $data['offer_formation']);
		$this->db->bind(':info_formation', $data['info_formation']);
		$this->db->bind(':account_type', $data['account_type']);
		$this->db->bind(':branche_app', $data['branche_app']);
		$this->db->bind(':rh_inv', $data['rh_inv']);
		$this->db->bind(':rh_delegue', $data['rh_delegue']);
		$this->db->bind(':address_facture', $data['address_facture']);
		$this->db->bind(':postalcode_facture', $data['postalcode_facture']);
		$this->db->bind(':city_facture', $data['city_facture']);
		$this->db->bind(':country_facture', $data['country_facture']);
		$this->db->bind(':address_comp_facture', $data['address_comp_facture']);
		$this->db->bind(':rgpd', $data['rgpd']);
		$this->db->bind(':region', $data['region']);
		$this->db->bind(':telephone', $data['telephone']);
		$this->db->bind(':remise_eligible', $data['remise_eligible']);

		if ($data['account_type'] == 'Professionnel') {
			$this->db->bind(':activity', $data['activity']);
			$this->db->bind(':fonction', $data['function']);
			$this->db->bind(':raison', $data['raison']);
			$this->db->bind(':conv_remise', $data['remise']);
			$this->db->bind(':raison_facture', $data['raison_facture']);
		}
		$this->db->bind(':nom_etab', $data['nom_etab']);

		if ($this->db->execute()) {
			return true;
		} else {
			return false;
		}
	}

}
