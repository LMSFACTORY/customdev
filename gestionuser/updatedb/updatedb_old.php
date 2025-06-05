<?php

require('../../../config.php');
require_once('../inc/init.php');

/*require_once($CFG->libdir.'/cronlib.php');*/

echo $OUTPUT->header();

global $CFG,$DB,$_SESSION;

if(!isloggedin()){
    redirect($CFG->wwwroot);
}


$resultat = execRequete("SELECT * FROM users");
$existing_user_array = [];

while($liste = $resultat->fetch()){

    if($liste != 0){
        $existing_user_array[] = $liste;
        // execRequete("DELETE FROM users WHERE rh_inv = 'Oui'");

    }
}


$requete = execRequete("SELECT siren,siret,GROUP_CONCAT(nom_etab,' ',siret,' ',code_naf) AS nom_etablissement,branche,region,segmentation,nom,prenom,email FROM csvdatatemptable GROUP BY email");


while($champs = $requete->fetch()){

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

    // execRequete("REPLACE INTO users VALUES (:username,:password,:firstname,:lastname,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue");

    // array(
    //     'username' => strtolower($new_email),
    //     'password' => $new_mdp,
    //     'firstname' => ucfirst($new_prenom),
    //     'lastname' => strtoupper($new_nom),
    //     'email' => strtolower($new_email),
    //     'fonction' => $fonction,
    //     'nom_etab' => $new_nom_etab,
    //     'siren' => $new_siren,
    //     'region' => $new_region,
    //     'segmentation' => $new_segmentation,
    //     'branche_app' => $new_branche,
    //    'num_adherent' => $num_adherent,
    //     'rh_inv' => 'Oui',
    //     'rh_delegue' => $rh_delegue
    //     ));
        /*
        $stmt = execRequete("REPLACE ...");
        if (!$stmt) {
            echo "\nPDO::errorInfo():\n";
            print_r($pdo->errorInfo());
         }
         */

}


/**
 * @desc This function is to differentiate between two array i.e data from Table users and csvtemptable
 * It takes data from both tables in form of arrays and then by using this function we compare the two and find the values that are in the table users and not in the csv file
 * @param $array1 , $array2
 */

function md_array_diff(array $array1, array $array2, array $_ = null) {
    $diff = [];
    $args = array_slice(func_get_args(), 1);

    foreach ($array1 as $key => $value) {
        foreach ($args as $item) {
            if (is_array($item)) {
                if (array_key_exists($key, $item)) {
                    if (is_array($value) && is_array($item[$key])) {
                        $tmpDiff = md_array_diff($value, $item[$key]);

                        if (!empty($tmpDiff)) {
                            foreach ($tmpDiff as $tmpKey => $tmpValue) {
                                if (isset($item[$key][$tmpKey])) {
                                    if (is_array($value[$tmpKey]) && is_array($item[$key][$tmpKey])) {
                                        $newDiff = array_diff($value[$tmpKey], $item[$key][$tmpKey]);
                                    } else if ($value[$tmpKey] !== $item[$key][$tmpKey]) {
                                        $newDiff = $value[$tmpKey];
                                    }

                                    if (isset($newDiff)) {
                                        $diff[$key][$tmpKey] = $newDiff;
                                    }
                                } else {
                                    $diff[$key][$tmpKey] = $tmpDiff;
                                }
                            }
                        }
                    } else if ($value !== $item[$key]) {
                        $diff[$key] = $value;

                    }
                } else {
                    $diff[$key] = $value;
                }
            }
        }
    }

    return $diff;
}


$diff_between_arrays = md_array_diff($existing_user_array,$new_user_array);

    for ($i=0; $i < count($diff_between_arrays) ; $i++) {
        $diff_array_email = $diff_between_arrays[$i]['email'];
        if ($diff_array_email != '' || $diff_array_email != null) {
            try {
                execRequete("DELETE FROM users WHERE email = '$diff_array_email' AND rh_inv= 'Oui'");
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }




for ($i=0; $i < count($new_user_array) ; $i++) {
    try {
        $user_exist = in_array($new_user_array[$i]['email'], $existing_user_array[$i], $strict = true);
        if ($user_exist) {
            // We do UPDATE Except MDP AND Email.

            $upd_firstname = $new_user_array[$i]['firstname'];
            $upd_lastname = $new_user_array[$i]['lastname'];
            $upd_fonction = $new_user_array[$i]['fonction'];
            $upd_nom_etab = $new_user_array[$i]['nom_etab'];
            $upd_siren = $new_user_array[$i]['siren'];
            $upd_region = $new_user_array[$i]['region'];
            $upd_segmentation = $new_user_array[$i]['segmentation'];
            $upd_branche_app = $new_user_array[$i]['branche_app'];
            $upd_num_adherent = $new_user_array[$i]['num_adherent'];
            $upd_rh_delegue = $new_user_array[$i]['rh_delegue'];
            $upd_email = $new_user_array[$i]['email'];

                execRequete("UPDATE users SET firstname = '$upd_firstname', lastname = '$upd_lastname', fonction = '$upd_fonction', nom_etab = '$upd_nom_etab', siren = '$upd_siren', region = '$upd_region', segmentation = '$upd_segmentation', branche_app = '$upd_branceh_app', num_adherent = '$upd_num_adherent' , rh_delegue = '$upd_rh_delegue' WHERE email = '$upd_email' AND rh_inv= 'Oui'");


        }else if (!$user_exist) {
            // WE do a Replace
            execRequete("REPLACE INTO users VALUES (:username,:password,:firstname,:lastname,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue)", $new_user_array[$i]);
        }

    } catch (\Throwable $th) {
        throw $th;
    }
}


execRequete("TRUNCATE TABLE csvdatatemptable");



// redirect($CFG->wwwroot.'/admin/tool/task/schedule_task.php?task=auth_db%5Ctask%5Csync_users', 'la base de données a été mise à jour avec succès', null, \core\output\notification::NOTIFY_SUCCESS);