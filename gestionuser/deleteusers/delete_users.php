<?php
use PhpOffice\PhpSpreadsheet\Calculation\Exception;
require('../../../config.php');
require_once('../inc/init.php');
echo $OUTPUT->header();
/**
 * Script d'import CSV.
 */
define('MYSQL_HOSTNAME', 'inpi-prod-sql1');
define('MYSQL_USERNAME', 'inpi-moodle-prod');
define('MYSQL_PASSWORD', 'Vxz0hn5bJpHzxeLU');
define('MYSQL_DATABASE', 'inpi-moodle-prod-ext');

define('MYSQL_TABLE_USERS', 'users_opco');
define('MYSQL_TABLE_CSVTEMPTABLE', 'csvdatatemptable');
/**
 * Permet d'activer les logs pour comprendre les cas d'erreur.
 */
define('ENABLE_LOG', TRUE);
define('ERROR_REPORT_RECEIVER', 'mohed.a@lmsfactory.com');
echo '<h1>Deleting users page</h1>';

    try {

    $pdo = getMysqlConnection();
    $existing_user_array = [];
    $sql_for_existing_users = "SELECT * FROM " . MYSQL_TABLE_USERS;

    $stmt =  $pdo->query($sql_for_existing_users);

    debug("<span>Obtenir tous les utilisateurs existants.</br></span>");
    $counter = 0;

    while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existing_user_array[] = $result;
    }
    debug("Utilisateurs existants =>" . count($existing_user_array) . "<br>");

    $new_user_array = get_new_users();

    debug("Nouveaux utilisateurs enregistrés => " . count($new_user_array) . "<br>");
	debug("Différenciation maintenant entre deux tables <br>");

		$users_to_delete = array();
		$found_counter = 0;
		$not_found_counter = 0;
		$test_user_array = array();
		$found = false;
        foreach($existing_user_array as $key => $value) {
			$delete_users = array();
			$delete_users['email'] = $value['email'];
            $user_email = $value['email']; //Existing user email
			for ($i=0; $i < count($new_user_array); $i++) {
				$new_user_email = $new_user_array[$i]['email'];
				if ($user_email == $new_user_email) {
					$found = true;
					$delete_users['to_delete'] = false;
					$found_counter++;
				}
			}
			if(!$found) {
				$delete_users['to_delete'] = true;
				$not_found_counter++;
			}
			$users_to_delete[] = $delete_users;
		}


		debug("Comptes à supprimer  => " . $not_found_counter . "<br>");

		foreach ($users_to_delete as $key => $value) {
			$email_to_delete = $value['email'];
			if ($value['to_delete']){
			debug("Deleting all the users in the Existing users which are not in the CSV file <br>");
			var_dump($email_to_delete);
			$sql = "DELETE FROM users_opco WHERE username = '$email_to_delete' AND rh_inv = 'Oui'";
			$pdo->exec($sql);
			}
		}

		debug("Suppression du tableau csvtemptable");
		$pdo->exec("DROP TABLE csvdatatemptable");
		echo('<a style="display: block;width: 150px;background: red;height: 50px;color: white;line-height: 3.7;margin: auto;text-decoration: none;text-align: center" href="/">Continuer</a>');
		exit(0);
} catch (\Throwable $th) {
//    throw $th;
}

    function debug($text):array
	{

        static $buffer = [];

        if ($text !== NULL) {

          $line = '[' . date('c') . "]: $text";
          $buffer[] = $line;
        }

        if (!ENABLE_LOG) {
          return $buffer;
        }

        if ($text !== NULL) {
          echo $line . PHP_EOL;
        }

        return $buffer;
    }





    function getMysqlConnection(): object {


        static $pdo = NULL;

        if ($pdo === NULL) {

          debug("Tentative de connexion à la base de donnée . <br>");

          $pdo = new PDO('mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          ]);

          debug("Connexion réussie");
        }

        return $pdo;
      }


      function get_new_users(): array {

        $pdo = getMysqlConnection();

        $sql_for_csvtemptable = "SELECT siren,siret,GROUP_CONCAT(nom_etab,' ',siret,' ',code_naf) AS nom_etablissement,branche,region,segmentation,nom,prenom,email FROM csvdatatemptable GROUP BY email";

        $requete = $pdo->query($sql_for_csvtemptable);
        while($champs = $requete->fetch(PDO::FETCH_ASSOC)){

            $new_email = $champs['email'];

            $new_prenom = $champs['prenom'];

            $new_nom = $champs['nom'];

            $new_nom_etab = $champs['nom_etablissement'];

            $new_siren = $champs['siren'];

            $new_siret = $champs['siret'];

            $new_region = $champs['region'];

            $new_segmentation = $champs['segmentation'];

            $new_branche = $champs['branche'];

            $mdp_prenom = strtolower(substr($new_prenom, 0, 3));

            $mdp_nom = strtolower(substr($new_nom, 0, 3));

            $mdp_siret = strtolower(substr($new_siret, 0, 2));

            $new_mdp = $mdp_prenom.$mdp_nom.$mdp_siret;

            $new_user_array[]  = array(
                'username' => strtolower($new_email),
                'password' => $new_mdp,
                'firstname' => ucfirst($new_prenom),
                'lastname' => strtoupper($new_nom),
                'email' => strtolower($new_email),
                'fonction' => $fonction,
                'nom_etab' => $new_nom_etab,
                'siren' => $new_siren,
                'region' => $new_region,
                'segmentation' => $new_segmentation,
                'branche_app' => $new_branche,
                'num_adherent' => $num_adherent,
                'rh_inv' => 'Oui',
                'rh_delegue' => $rh_delegue
            );

        }

        return $new_user_array;
      }