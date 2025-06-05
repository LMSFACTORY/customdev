<?php
require_once '../../config.php';
require_once './db/Database.php';

class customProviders
{

	private $db;
	private string $table;
	public static array $cmids = [7242, 7243, 7244, 7245, 7246, 7247];
private array $countryCodes;

	private array $dateFields = [
		'date_prediagnostic',
		'date_formation_inpi',
		'date_formation_outils',
		'date_formation_observateur',
		'date_audit_pedagogique',
		'date_validite_document',
	];

	public function __construct()
	{
		$this->db = new Database;
		$this->table = "users_opco";

$customValues = require './customValues.php';
		$this->countryCodes = $customValues['countryCodes'] ?? [];
	}

	/**
	 * This method helps to check if a provider exists in the database.
	 *
	 * @param string $email
	 * @return bool
	 */
	public function checkProvider(string $email): bool
	{
		// Check if the provider exists in the database
		// Return true if the provider exists, false otherwise
		$this->db->query("SELECT * FROM $this->table WHERE username = :email");
		$this->db->bind(':email', $email);
		$this->db->execute();
		$row = $this->db->single();

		if ($row) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This method helps to get a provider from the database.
	 *
	 * @param string $email
	 * @return array
	 */

	public function getProvider(string $email): array
	{
		// Get the provider from the database
		// Return the provider as an array
		$this->db->query("SELECT * FROM $this->table WHERE username = :email");
		$this->db->bind(':email', $email);
		$this->db->execute();
		$row = $this->db->single();

		if ($row) {
			$provider = (array) $row;
			return $this->stripPhoneCode($provider);
		} else {
			return [];
		}
	}

	/*
	 * This method helps to retrieve the total number of providers in the database.
	 * @return mixed
	 * @throws dml_exception
	 */

	public function getProvidersCount(): mixed
	{
		$sql = "SELECT COUNT(*) as total FROM $this->table WHERE is_provider = 'Oui'";
		$this->db->query($sql);
		$this->db->execute();
		$result = $this->db->single();
		return $result->total;
	}

	/**
	 * This method helps to retrieve all providers from the database.
	 * It also retrieves the internal ID of the provider.
	 * The method also supports filtering, pagination, and sorting.
	 *
	 * @param array $filters
	 * @param int|null $limit
	 * @param int|null $offset
	 * @return array
	 * @throws dml_exception
	 */

	public function getAllProviders(array $filters = [], int $limit = null, int $offset = null): array
	{

		global $DB;
		// Start with the base query ensuring is_provider is always required
		$query = "SELECT * FROM $this->table WHERE is_provider = 'Oui'";

		$params = [];

		// Append additional filters if provided
		if (!empty($filters)) {
			$filterConditions = [];

			foreach ($filters as $field => $value) {
				// Validate $field if necessary to avoid SQL injection risks
				$filterConditions[] = "$field LIKE :$field";
				$params[$field] = "%$value%";
			}

			// Add filters wrapped in parentheses with OR condition
			if (!empty($filterConditions)) {
				$query .= " AND (" . implode(" OR ", $filterConditions) . ")";
			}
		}
		$query .= " ORDER BY firstname ASC ";

		// Append pagination clauses
		if ($limit !== null) {
			$query .= " LIMIT :limit";
			$params['limit'] = (int) $limit;
		}
		if ($offset !== null) {
			$query .= " OFFSET :offset";
			$params['offset'] = (int) $offset;
		}

		// Prepare the query
		$this->db->query($query);

		// Bind filter values
		foreach ($params as $field => $value) {
			$this->db->bind(":$field", $value);
		}

		// Bind pagination values as integers
		if ($limit !== null) {
			$this->db->bind(":limit", $limit, PDO::PARAM_INT);
		}
		if ($offset !== null) {
			$this->db->bind(":offset", $offset, PDO::PARAM_INT);
		}

		// Execute and return the result set
		$this->db->execute();
		$user_from_external = $this->db->resultSet();

		$users = [];
		$user_from_internal = [];
		foreach ($user_from_external as $key => $value) {
			$sql = "SELECT u.id, u.email FROM {user} u WHERE email = :email";
			$user_from_internal[] = $DB->get_record_sql($sql, ['email' => $value->email]);
			$value->id = $user_from_internal[$key]->id;

		}
		return $user_from_external;
	}

	/**
	 * This method helps to count the number of providers in the database.
	 *
	 * @param array $filters
	 * @return mixed
	 */
	public function countProviders(array $filters = []): mixed
	{
		// Start with the base query that counts the providers.
		$query = "SELECT COUNT(*) as total FROM $this->table WHERE is_provider = 'Oui' ";

		// Prepare an array for binding filter values.
		$params = [];

		// Append additional filters if provided.
		if (!empty($filters)) {
			foreach ($filters as $field => $value) {
				// Using LIKE with wildcards for consistency with getAllProviders.
				$query .= "AND $field LIKE :$field ";
				$params[$field] = "%$value%";
			}
		}

		// Prepare the query.
		$this->db->query($query);

		// Bind the filter values.
		foreach ($params as $field => $value) {
			$this->db->bind(":$field", $value);
		}

		// Execute the query.
		$this->db->execute();

		// Fetch the result. Method single() returns a single record.
		$result = $this->db->single();

		// Return the total count.
		return $result->total;
	}

	/**
	 * This method manages the fields to ignore when inserting into the database.
	 *
	 * @param array $data
	 * @return array
	 */
	private function fields_to_ignore(array $data): array
	{
		// Fields to ignore when inserting into the database
		$ignore = [
			'submit_provider',
			'isErrors',
			'country_code',
			'country_code_fix',
			'siret',
			'function',
			'update_provider',
			'recommande_par',
			'check_date_prediagnostic',
			'check_date_formation_inpi',
			'check_date_formation_outils',
			'check_date_formation_observateur',
			'check_date_audit_pedagogique',
			'check_date_validite_document',
		];
		foreach ($data as $key => $value) {
			if (str_contains($key, '_err')) {
				unset($data[$key]);
			}

			if (in_array($key, $ignore)) {
				unset($data[$key]);
			}
		}
		return $data;
	}

	/**
	 * This function helps register a provider after the form has been submitted with necessary controls.
	 *
	 * @param array $data
	 * @return array
	 * @throws Exception
	 */
	public function registerProvider(array $data): array
	{
		// Lowercase the email
		$data['email'] = strtolower($data['email']);
		$data['username'] = $data['email'];

		$data['nom_etab'] = $data['siret'];
		$data['fonction'] = $data['function'];
		$data['branche_app'] = 'E-commerce';

		$data = $this->error_handler($data);

		// Accumulate the country code and the phone number

		if (!empty($data['telephone_fix'])) {
			$data['telephone_fix'] = $data['country_code_fix'] . " " . $data['telephone_fix'];
		}

		$data['telephone'] = $data['country_code'] . " " . $data['telephone'];

		if ($data['isErrors']) {
			$data = $this->stripPhoneCode($data);
			return [
				"err_data" => $data,
			];
		}

		// Insert the provider into the database
		try {
			// Remove the fields to ignore
			$data = $this->fields_to_ignore($data);
			// Convert date fields to timestamps
			$data = $this->dateToTimestamp($data);

			$data['is_provider'] = 'Oui';
			// Insert into database.
			$this->insertProvider($data);
			return [
				"success" => [
					'message' => 'Provider registered successfully',
					'provider' => $data,
					'status' => "success",
				],
			];
		} catch (Exception $e) {
			$data['db_err'] = $e->getMessage();
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * This method helps to handle errors in the form data.
	 *
	 * @param $data
	 * @return array
	 */

	public function error_handler($data): array
	{
		// Check every data field and return an error message if the field is empty
		// Return the error message as an array
		foreach ($data as $key => $value) {
			if (
				$key == 'submit_provider' ||
				$key == 'update_provider' ||
				$key == 'address_comp' ||
				$key == 'autre_employeur' ||
				$key == 'commentaire' ||
				$key == 'statut_dossier' ||
				$key == 'country_code' ||
				$key == 'country_code_fix' ||
				$key == 'telephone_fix' ||
				$key == 'recommande_par'
			) {
				continue;
			}

			if (empty($value)) {
				$data[$key . "_err"] = "Veuillez saisir une valeur valide. ";
			}

			// Allow spaces in telephone and telephone_fix fields
			if (($key == 'telephone' || $key == 'telephone_fix') && !is_numeric(str_replace(' ', '', $value))) {
				$data[$key . "_err"] = "Le numéro de téléphone doit être numérique.";
			}

			// specific control for siret and nom_etab
			if ($key == 'nom_etab' || $key == 'siret') {
				// Check if the SIRET number is valid and should not be exactly 14 characters
				if (strlen($value) !== 14) {
					$data[$key . "_err"] = "Le numéro SIRET doit contenir 14 chiffres.";
					if (!is_numeric($value)) {
						$data[$key . "_err"] = "Le numéro SIRET doit être numérique.";
					}
				}
			}

			// Check if the email is valid
			if ($key == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$data[$key . "_err"] = "Format courriel invalide.";
			}

			// Check if statut is valid
			if ($key == 'statut' && ($value = "0" || $value = 0)) {
				$data[$key . "_err"] = "Sélectionner un statut";
			}
		}
		$data['isErrors'] = (bool) array_filter($data, function ($key) {
			return str_contains($key, '_err');
		}, ARRAY_FILTER_USE_KEY);
		return $data;
	}

	/**
	 * This method helps to insert a provider into the database.
	 *
	 * @param $data
	 */

	public function insertProvider($data): void
	{
		$sql = "INSERT INTO $this->table";
		$columns = [];
		$placeholders = [];

		foreach ($data as $key => $value) {
			// Ensure keys are valid for SQL placeholders (no special characters)
			$sanitizedKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
			$columns[] = "`$sanitizedKey`"; // Backticks to escape column names
			$placeholders[] = ":$sanitizedKey";
		}

		$sql .= " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
		$this->db->query($sql);

		// Bind parameters using sanitized keys
		foreach ($data as $key => $value) {
			$sanitizedKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
			$this->db->bind(":$sanitizedKey", $value);
		}
		$this->db->execute();
	}

	/**
	 * This method helps to update a provider after the form has been submitted with necessary controls.
	 *
	 * @throws Exception
	 */
	public function updateProvider($data): array
	{
		$data['nom_etab'] = $data['siret'];
		$data['fonction'] = $data['function'];
		$data['is_provider'] = 'Oui';

		// Validate and handle errors
		$data = $this->error_handler($data);

		// Accumulate the country code and the phone number
		if (!empty($data['telephone_fix'])) {
			$data['telephone_fix'] = $data['country_code_fix'] . " " . $data['telephone_fix'];
		}
		$data['telephone'] = $data['country_code'] . " " . $data['telephone'];

		if ($data['isErrors']) {
			$data = $this->stripPhoneCode($data);
			return [
				"err_data" => $data,
			];
		}

		// Define checkbox fields with default values
		$checkboxFields = [
			'prediagnostic_PI' => 'Non',
			'parrain' => 'Non',
			'pass_pi' => 'Non',
			'facile_collaboration' => 'Non',
			'coaching' => 'Non',
			'formation_academie' => 'Non',
		];

		// Merge checkbox fields into $data, preserving 'Oui' if present
		foreach ($checkboxFields as $field => $defaultValue) {
			$data[$field] = isset($data[$field]) && $data[$field] === 'Oui' ? 'Oui' : $defaultValue;
		}

		try {
			// Reset date values to NULL if they are empty
			$data = $this->resetDateValues($data);

			// Remove fields to ignore
			$data = $this->fields_to_ignore($data);

			// Convert date fields to timestamps
			$data = $this->dateToTimestamp($data);

			// Update the provider in the database
			$this->updateProviderInDB($data);

			return [
				"success" => [
					'message' => 'Provider updated successfully',
					'provider' => $data,
					'status' => "success",
				],
			];
		} catch (Exception $e) {
			$data['db_err'] = $e->getMessage();
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * This method helps to update a provider in the database.
	 *
	 * @param $data
	 */
	public function updateProviderInDB($data): void
	{
		// Update the provider in the database
		// Return the updated provider as an array
		$sql = "UPDATE $this->table SET ";
		$set = [];
		$where = [];

		foreach ($data as $key => $value) {
			// Ensure keys are valid for SQL placeholders (no special characters)
			$sanitizedKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
			$set[] = "`$sanitizedKey` = :$sanitizedKey";
		}

		$sql .= implode(', ', $set);
		$sql .= " WHERE email = :email";
		$this->db->query($sql);

		// Bind parameters using sanitized keys
		foreach ($data as $key => $value) {
			$sanitizedKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
			$this->db->bind(":$sanitizedKey", $value);
		}
		$this->db->execute();
	}

	/**
	 * This method helps to strip the phone code from the phone number.
	 *
	 * @param $data
	 * @return array
	 */
	private function stripPhoneCode($data): array
	{
		// Full associative country codes (unchanged, kept as requested)
		$countryCodes = $this->countryCodes;
		// Helper function to strip code from a number
		$strip = function ($number, &$codeField) use ($countryCodes) {
			$original = trim($number);
			$normalized = str_replace(' ', '', $original); // Remove all spaces

			foreach ($countryCodes as $country => $code) {
				$normalizedCode = str_replace(' ', '', $code);

				if (strpos($normalized, $normalizedCode) === 0) {
					$codeField = $code;

					// Get the length of the dial code
					$codeLength = strlen($normalizedCode);

					// Get the rest of the digits (after the code)
					$restNormalized = substr($normalized, $codeLength);

					// Now extract that part from the original number (preserving spacing)
					$restWithSpaces = preg_replace('/^' . preg_quote($code, '/') . '\s*/', '', $original);

					return trim($restWithSpaces);
				}
			}

			// No match found, return original
			return $original;
		};

		// Process mobile number
		if (!empty($data['telephone'])) {
			$data['country_code'] = '';
			$data['telephone'] = $strip($data['telephone'], $data['country_code']);
		}

		// Process landline number
		if (!empty($data['telephone_fix'])) {
			$data['country_code_fix'] = '';
			$data['telephone_fix'] = $strip($data['telephone_fix'], $data['country_code_fix']);
		}

		return $data;
	}
	/**
	 * This method helps to check if a string is numeric.
	 *
	 * @param $str
	 * @return bool
	 */

	private function isNumeric($str): bool
	{
		// Remove leading and trailing whitespace
		$trimmed = trim($str);

		// If the string is empty after trimming, accept it
		if ($trimmed === '') {
			return true;
		}

		// Otherwise, check if it consists only of digits
		return preg_match('/^[0-9]+$/', $trimmed) === 1;
	}

	/**
	 * This method helps to delete a provider.
	 * Only the provider's fields are updated and are set to NULL.
	 *
	 * @param string $email
	 */

	public function deleteProvider(string $email): void
	{
		$providerFields = [
			'is_provider',
			'statut_dossier',
			'recommande_par',
			'experience_en_PI',
			'niveau_etudes',
			'experience_formation',
			'prediagnostic_PI',
			'parrain',
			'pass_pi',
			'facile_collaboration',
			'coaching',
			'formation_academie',
			'date_prediagnostic',
			'date_formation_inpi',
			'date_formation_outils',
			'date_formation_observateur',
			'date_audit_pedagogique',
			'session_concern',
			'domain_excellence',
			'zone_intervention',
			'commentaire',
			'statut_dossier',
			'date_validite_document',
			'autre_employeur',
		];
		// Update the following profilefields in the database and set to NULL.
		$sql = "UPDATE $this->table SET ";
		$fieldsToNull = [];
		foreach ($providerFields as $field) {
			$fieldsToNull[] = "$field = NULL";
		}
		$sql .= implode(', ', $fieldsToNull);
		$sql .= " WHERE username = :email";

		$this->db->query($sql);
		$this->db->bind(':email', $email);
		$this->db->execute();
	}

	/**
	 * This method helps to convert date fields to timestamps.
	 *
	 * @param array $data
	 * @return array
	 */

	public function dateToTimestamp(array $data): array
	{
		foreach ($this->dateFields as $field) {
			// Check if the key exists in $data to avoid undefined indexes
			if (array_key_exists($field, $data)) {
				$value = trim($data[$field]);
				// Set to zero if the value is null or empty
				if ($data[$field] === null || $value === '') {
					$data[$field] = 0;
				} else {
					// Convert valid date string to a timestamp
					$timestamp = strtotime($data[$field]);
					// If strtotime fails, you can default to 0 instead of boolean false
					$data[$field] = ($timestamp !== false) ? $timestamp : 0;
				}
			}
		}
		return $data;
	}

	/**
	 * This method helps to convert timestamps to date fields.
	 *
	 * @param array $data
	 * @return array
	 */
	public function timestampToDate(array $data): array
	{
		foreach ($this->dateFields as $field) {
			// Check if the key exists in $data to avoid undefined indexes
			if (array_key_exists($field, $data)) {
				// Example: set to zero if the value is NULL or empty
				if ($data[$field] === null || trim($data[$field]) === '') {
					$data[$field] = 0;
				}
				if ($data[$field] != 0) {
					$data[$field] = date('d-m-Y', $data[$field]);
				}

			}
		}
		return $data;
	}

	/**
	 * Returns an HTML table of a user's assignment files across multiple cmids.
	 * @param int $userid The user ID for filtering submissions.
	 * @return void The rendered HTML table.
	 * @throws coding_exception
	 * @throws dml_exception
	 * @throws required_capability_exception
	 */
	public static function get_assignments_files_table_html(int $userid)
	{
		global $DB, $CFG, $OUTPUT;
		require_once($CFG->libdir . '/tablelib.php');

		// 1. Prepare the Table
		//    We add a third column for 'Date' (upload date).
		$table = new flexible_table('multi-assign-submissions-table');
		$columns = ['assignmentname', 'filename', 'filedate'];
		$headers = [
			// Adjust the strings as needed
			'Document',       // assignment name
			'Télécharger',    // file links
			'Date',           // file creation date
		];
		$table->define_columns($columns);
		$table->define_headers($headers);
		// $table->collapsible(true);
		$table->sortable(true, 'assignmentname', SORT_ASC);
		$table->pageable(false);

		// Let the table do its setup
		$table->setup();

		// 2. Build Table Data
		$fs = get_file_storage();

		// Loop through the cmids stored in self::$cmids
		foreach (self::$cmids as $cmid) {
			// Fetch the course module and assignment
			$cm = get_coursemodule_from_id('assign', $cmid, 0, false, MUST_EXIST);
			$assign = $DB->get_record('assign', ['id' => $cm->instance], '*', MUST_EXIST);
			$context = context_module::instance($cm->id);

			// If needed, check capability:
			// require_capability('mod/assign:grade', $context);

			// Get user submissions for this assignment
			$submissions = $DB->get_records('assign_submission', [
				'assignment' => $assign->id,
				'userid' => $userid,
				'status' => 'submitted'
			]);

			// Prepare arrays to accumulate all file data for this assignment
			$filelinks = [];
			$filedates = [];

			// 2.a. Gather all files from all submissions into arrays
			foreach ($submissions as $submission) {
				$files = $fs->get_area_files(
					$context->id,
					'assignsubmission_file',
					'submission_files',
					$submission->id,
					'filename',
					false
				);

				foreach ($files as $file) {
					if ($file->is_directory()) {
						continue;
					}

					// Build the pluginfile URL
					$filepath = moodle_url::make_pluginfile_url(
						$file->get_contextid(),
						$file->get_component(),
						$file->get_filearea(),
						$file->get_itemid(),
						$file->get_filepath(),
						$file->get_filename()
					);

					// Prepare link and date
					$filelinks[] = html_writer::link($filepath, format_string($file->get_filename()));

					// Format file creation time
					$filedate = userdate($file->get_timecreated());
					$filedates[] = $filedate;
				}
			}

			// 2.b. If no files were found for this assignment, skip creating a row
			if (empty($filelinks) && empty($filedates)) {
				continue;
			}

			// 2.c. Combine data into single row (one row per assignment)
			// We use <br> to separate multiple files/dates in the same cell
			$row = [];
			$row['assignmentname'] = format_string($assign->name);
			$row['filename'] = implode('<br>', $filelinks);
			$row['filedate'] = implode('<br>', $filedates);

			// 2.d. Add this row to the table
			$table->add_data($row);
		}

		// 3. Output the Table
		$table->print_nothing_to_display = true; // If no data, shows 'Nothing to display'
		$table->finish_output();
	}


	private function resetDateValues(array $data)
	{
		// if the check is 'on' then the date will be set to the value of the date field else it will be set to empty
		foreach ($this->dateFields as $field) {
			if (!isset($data['check_' . $field]) || $data['check_' . $field] != 'on' || empty($data[$field])) {
				$data[$field] = '';
			}
		}
		return $data;
	}

}