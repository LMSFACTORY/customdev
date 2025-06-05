<?php

require('../../../config.php');
echo $OUTPUT->header();
?>
<main class="container" style="border: 1px solid #f8f9fa; padding-bottom: 70px;">  
    <div class="bg-light p-5 rounded mt-3">
        <h1>Exemple de texte à mettre</h1>
        <p class="lead">Exemple de texte à mettre Exemple de texte à mettre Exemple de texte à mettre</p>
    </div>
    <div style="margin-top: 15px">
        <a class="btn btn-lg btn-primary" href="<?= $CFG->wwwroot ?>" role="button">Retour à l'accueil</a>
        <a style="float: right; color: #fff; background: #1a1c36" class="btn btn-lg" href="<?= $CFG->wwwroot.'/synchro/importcsvopco.php' ?>" role="button">Importer les données du fichier dans la bdd</a>
    </div>
</main>
