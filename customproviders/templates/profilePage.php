<?php
$arr_excellence = !empty($provider->domain_excellence) ? explode(',', $provider->domain_excellence) : [];
$arr_zone = !empty($provider->zone_intervention) ? explode(',', $provider->zone_intervention) : [];
$modalId = 'deleteProviderModal_' . $provider->id;
// Helper function to display "Non" for empty, null or 0 values
function displayValue($value)
{
    return (empty($value) || $value === 0) ? 'Non' : $value;
}
?>

<div class="container mt-5">
    <!-- Section header -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mr-3">
                <h1 class="d-flex">
                    <?php echo $title; ?>
                </h1>
                <a href="/customdev/customproviders/base_provider.php" id="btn_provider_base"
                    class="btn btn-primary">Voir la base
                    fournisseur
                </a>
            </div>
            <div class="dropdown custom-menu btn-group dropleft">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php if ($actions['can_manage']) { ?>
                    <a class="dropdown-item" href="update_provider.php?email=<?php echo $provider->email ?>">Modifier
                        le
                        profil
                    </a>
                    <a class="dropdown-item" href="/customdev/customproviders/upload_documents.php?email=<?php echo
                            $provider->email ?>">Ajouter
                        des pièces
                        administratives
                    </a>
                    <?php } ?>
                    <?php if ($actions['can_delete']) { ?>
                    <a class="dropdown-item deleteProviderBtn" href="#" data-toggle="modal"
                        data-target="#<?php echo $modalId; ?>">Retirer le fournisseur</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Dossier -->
    <div id="section_document">
        <h3>Dossier</h3>
        <div class="row">
            <div class="col-md-6">
                <span><b>Etat du dossier :</b></span><br>
                <div class="status_badge text-center p-2 bg-<?php echo $provider->statut_dossier == 'Incomplet' ? 'warning' :
                    (($provider->statut_dossier == 'Complet' ? 'success' : 'danger')) ?>" disabled>
                    <?php
                    if ($provider->statut_dossier == 'Complet') {
                        echo 'Complet';
                    } else if ($provider->statut_dossier == 'Incomplet') {
                        echo 'Incomplet';
                    } else {
                        echo 'Indisponible';
                    }
                    ?>
                </div>
                <span><b>Validité des documents :</b> <?php echo displayValue($provider->date_validite_document); ?>
                </span>
            </div>
            <div class="col-md-6">
                <span><b>Commentaires:</b></span><br>
                <p><?php echo displayValue($provider->commentaire); ?></p>
            </div>
        </div>
    </div>
    <hr>
    <!-- Section Informations personnelles -->
    <div id="section_informations">
        <h3>Informations personnelles</h3>
        <div class="row">
            <!-- Column Identité -->
            <div class="col-md-4">
                <h3>Identité</h3>
                <div class="info_cell">
                    <p class="font-weight-bold">Status :</p>
                    <p><?php echo displayValue($provider->statut); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">Fonction :</p>
                    <p><?php echo displayValue($provider->fonction); ?></p>
                </div>
<div class="info_cell">
                    <p class="font-weight-bold">Siret :</p>
                    <p><?php echo displayValue($provider->nom_etab); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">Organisme associé :</p>
                    <p><?php echo displayValue($provider->raison); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">Autre employeur :</p>
                    <p><?php echo displayValue($provider->autre_employeur); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">Recommandé par :</p>
                    <p><?php echo displayValue($provider->recommande_par); ?></p>
                </div>
            </div>
            <!-- Column Contact -->
            <div class="col-md-4">
                <h3>Contact</h3>
                <div class="info_cell">
                    <p class="font-weight-bold">Mel :</p>
                    <p><?php echo displayValue($provider->email); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">Téléphone fixe :</p>
                    <p><?php echo displayValue($provider->telephone_fix); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">Téléphone portable :</p>
                    <p><?php echo displayValue($provider->telephone); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">Adresse :</p>
                    <p><?php echo displayValue($provider->address); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">Pays :</p>
                    <p><?php echo displayValue($provider->country); ?></p>
                </div>
            </div>
            <!-- Column Intervention -->
            <div class="col-md-4">
                <h3>Périmètre d'intervention</h3>
                <div class="info_cell">
                    <?php foreach ($arr_intervention as $key => $value) {
                        echo ($provider->$key == 'Oui') ?
                            "<div class='badge bg-secondary m-1 p-2'> $value </div>"
                            : '';
                        ?>
                    <?php } ?>
                </div>

                <div class="info_cell">
                    <p class="font-weight-bold">A reçu une formation pré-diagnostic</p>
                    <p><?php echo displayValue($provider->date_prediagnostic); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">A reçu une formation de formateur par l'INPI</p>
                    <p><?php echo displayValue($provider->date_formation_inpi); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">A reçu une formation sur les outils de classe virtuelle</p>
                    <p><?php echo displayValue($provider->date_formation_outils); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">A participé en tant qu'observateur à une formation</p>
                    <p><?php echo displayValue($provider->date_formation_observateur); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">A bénéficié d'un audit pédagogique</p>
                    <p><?php echo displayValue($provider->date_audit_pedagogique); ?></p>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <!-- Section Parcours -->
    <div id="section_parcours">
        <h3>Parcours</h3>
        <div class="row">
            <div class="col-md-6">
                <h3>Expérience</h3>
                <div class="info_cell">
                    <p class="font-weight-bold">Nombre d'années d'expérience en PI</p>
                    <p><?php echo displayValue($provider->experience_en_PI); ?></p>
                </div>
                <div class="info_cell">
                    <p class="font-weight-bold">Expérience en animation de formation</p>
                    <p><?php echo displayValue($provider->experience_formation); ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <h3>Formation</h3>
                <div class="info_cell">
                    <p class="font-weight-bold">Niveau d'études, diplômes, certification</p>
                    <p><?php echo displayValue($provider->niveau_etudes); ?></p>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <!-- Section Excellence -->
    <div id="section_excellence">
        <h3>Domain d'excellence</h3>
        <div class="row">
            <?php if (!empty($arr_excellence)): ?>
            <?php foreach ($arr_excellence as $key => $value): ?>
            <div class='tag m-1 selected' style="cursor:initial;"><?php echo $value; ?></div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="col-md-12">Non</div>
            <?php endif; ?>
        </div>
    </div>
    <hr>
    <!-- Section Excellence -->
    <div>
        <h3>Zones d'intervention</h3>
        <div class="row">
            <?php if (!empty($arr_zone)): ?>
            <?php foreach ($arr_zone as $key => $value): ?>
            <div class='tag m-1 selected' style="cursor:initial;"><?php echo $value; ?></div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="col-md-12">Non</div>
            <?php endif; ?>
        </div>
    </div>
    <hr>
    <!-- Section  Documents -->
    <div id="section_uploaded_files">
        <h3>Pièces administratives</h3>
        <div class="row">
            <div class="col-md-12">
                <?php
                $files_html = customProviders::get_assignments_files_table_html($userid);
                echo !empty($files_html) ? $files_html : 'Non';
                ?>
                <a class="btn btn-primary float-right" href="/customdev/customproviders/upload_documents.php?email=<?php echo
                    $provider->email ?>">Ajouter
                    des pièces
                    administratives
                </a>
            </div>
        </div>
    </div>
    <hr>

    <!-- Section blocks -->
    <div id="section_blocks">
        <?php echo $block; ?>
    </div>
</div>

<!-- Delete Provider Modal -->
<div class="modal fade" id="<?php echo $modalId; ?>" role="dialog" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Retirer le fournisseur</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modalBodyContent">
                Voulez-vous vraiment supprimer ce fournisseur ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button class="btn btn-danger delete-provider-btn" data-email="<?php echo $provider->email; ?>"
                    data-id="<?php echo $userid; ?>">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>