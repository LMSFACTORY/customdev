<?php

$arr = [
    'prediagnostic_PI' => 'Prédiagnostic PI',
    'pass_pi' => 'Pass PI',
    'coaching' => 'Coaching',
    'parrain' => 'Parrain / Marraine',
    'facile_collaboration' => 'Facilitation Collaborative Alliance PI',
    'formation_academie' => 'Formation Académie',
];

$statut_dossier = [
    'Complet',
    'Incomplet',
    'Indisponible',
];

$status = [
    'Honoraire',
    'Bénévole',
    'Vacataire',
    'Interne INPI',
];


// Helper function to display a value or "Non" if the value is null/zero/empty.
function displayValue($value, $field = null)
{
    // Check if the field is 'nom_etab' and the value is 0 or empty
    if ($field === 'nom_etab' && (empty($value) || $value === 0)) {
        return ''; // Return nothing
    }

    return (empty($value) || $value === 0) ? 'Non' : $value;
}
// Example variables (you need to set these in your controller)
$current_page = $current_page ?? 1; // current page number
$total_pages = $total_pages ?? 5; // total pages available (example)
// Calculate the start and end page numbers for the sliding window.
$visiblePages = 5;

// Calculate the start and end page numbers for the sliding window.
// Center the current page as much as possible.
$start = max(1, $current_page - floor($visiblePages / 2));
$end = min($total_pages, $start + $visiblePages - 1);

// Adjust start if we're near the end.
$start = max(1, $end - $visiblePages + 1);


$query_string = '';
if (!empty($all_filters)) {
    // Remove page from filters if it's present, so it doesn't conflict.
    $filters = $all_filters;
    unset($filters['page']);
    $query_string = '&' . http_build_query($filters);
}


?>

<div class="container-fluid bg-white pt-5">
    <h1 class="d-flex flex-column"><?php echo $title; ?>
        <span style="font-size:0.85rem;">
            <?php
            if ($is_filtered) {
                echo 'Fournisseurs filtrés : ';
            } else {
                echo 'Total des fournisseurs : ';
            }
            echo $provider_filtered_count
                ?>
        </span>
    </h1>

    <?php if ($actions['can_manage']) { ?>
        <a href="/customdev/customproviders/index.php" class="btn btn-primary">Ajouter un fournisseur</a>
    <?php } ?>
    <div class="row mt-3 pl-0">
        <div class="col-md-12">
            <div class="d-flex">
                <a type="button" class="form-trigger" data-toggle="collapse" data-target="#collapseExample"
                    aria-expanded="true" aria-controls="collapseExample">
                    Rechercher un fournisseur
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </a>
            </div>
            <form action="/customdev/customproviders/base_provider.php" method="get" class="collapse show"
                id="collapseExample">
                <div id="filter_columns" class="row">
                    <div id="filter_column_left" class="d-flex flex-column col-md-4">
                        <h3>Profil</h3>
                        <div class="form-group">
                            <label for="firstname">Prénom</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom"
                                value="<?php echo $all_filters['firstname'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Nom</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Nom"
                                value="<?php echo $all_filters['lastname'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Courriel</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Courriel"
                                value="<?php echo $all_filters['email'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="statut_dossier">Etat du dossier</label>
                            <select class="form-control" id="statut_dossier" name="statut_dossier">
                                <option value="">Sélectionner</option>
                                <?php foreach ($statut_dossier as $statut) { ?>
                                    <option value="<?php echo $statut; ?>" <?php if (
                                           isset($all_filters['statut_dossier'])
                                           && $all_filters['statut_dossier'] == $statut
                                       ) {
                                           echo 'selected';
                                       } ?>>
                                        <?php echo $statut; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div id="filter_column_center" class="d-flex flex-column col-md-4">
                        <h3>Informations Professionnelles</h3>
                        <div class="form-group">
                            <label for="raison">Organisme associé</label>
                            <input type="text" class="form-control" id="raison" name="raison" placeholder="Ex : INPI"
                                value="<?php echo $all_filters['raison'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="siret">Siret</label>
                            <input type="text" class="form-control" id="siret" name="nom_etab" placeholder="SIRET"
                                value="<?php echo $all_filters['nom_etab'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="fonction">Fonction</label>
                            <select class="form-control" id="fonction" name="fonction">
                                <option value="">Sélectionner</option>
                                <?php foreach ($functions as $function) { ?>
                                    <option value="<?php echo $function; ?>" <?php if (
                                           isset($all_filters['fonction']) &&
                                           $all_filters['fonction'] == $function
                                       ) {
                                           echo 'selected';
                                       } ?>>
                                        <?php echo $function; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="statut">Statut</label>
                            <select class="form-control" id="statut" name="statut">
                                <option value="">Sélectionner...</option>
                                <?php foreach ($status as $value) { ?>
                                    <option value="<?php echo $value; ?>" <?php if (
                                           isset($all_filters['statut']) &&
                                           $all_filters['statut'] == $value
                                       ) {
                                           echo 'selected';
                                       } ?>>
                                        <?php echo $value; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div id="filter_column_right" class="d-flex flex-column col-md-4">
                        <h3>Intervention</h3>
                        <?php foreach ($arr as $key => $value) { ?>
                            <label class="form-check-label container__input" for="<?php echo $key; ?>">
                                <?php echo $value; ?>
                                <input class="form-check-input" type="checkbox" value="Oui" id="<?php echo $key; ?>"
                                    name="<?php echo $key; ?>" <?php echo $all_filters[$key] == 'Oui' ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                            </label>
                        <?php } ?>
                    </div>
                    <div id="filter_column_bottom_left" class="col-md-4">
                        <h3>Localisation ET domaines</h3>
                        <div class="form-group">
                            <label for="domain_excellence">Domaines d'excellence</label>
                            <select class="form-control" id="domain_excellence" name="domain_excellence" multiple>
                                <option value="">Sélectionner</option>
                                <?php foreach ($domain_excellence as $domain) { ?>
                                    <?php for ($i = 0; $i < count($domain); $i++) { ?>
                                        <option value="<?php echo $domain[$i]; ?>"><?php echo $domain[$i]; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="filter_column_bottom_center">
                        <h3 style="visibility: hidden;">Localisation ET domaines</h3>
                        <div class="form-group">
                            <label for="domain_excellence">Zone d'intervention</label>
                            <select class="form-control" id="zone_intervention" name="zone_intervention" multiple>
                                <option value="">Sélectionner</option>
                                <?php foreach ($regions as $department => $region) { ?>
                                    <?php for ($i = 0; $i < count($region); $i++) { ?>
                                        <option value="<?php echo $region[$i]; ?>"><?php echo $region[$i]; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <p class="text-small alert alert-info" style="margin-left:15px; width:fit-content;">
                    <b id="yui_3_18_1_1_1740415476833_25">Note:</b> Maintenez la touche Ctrl pour une sélection
                    multiple
                </p>
                <button class="btn btn-outline-primary" type="submit" name="filter">Rechercher</button>
                <button class="btn btn-outline-danger" type="submit" name="filter_reset">Réinitialiser les
                    filtres
                </button>
            </form>
        </div>
    </div>
</div>

<div class="provider__wrapper">
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <!-- Previous Page Link -->
                <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?><?php echo $query_string; ?>"
                        tabindex="-1">Previous</a>
                </li>

                <!-- Link to first page if it's not in the visible window -->
                <?php if ($start > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1<?php echo $query_string; ?>">1</a>
                    </li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Page Number Links for the visible range -->
                <?php for ($pageNum = $start; $pageNum <= $end; $pageNum++): ?>
                    <li class="page-item <?php echo ($pageNum == $current_page) ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="?page=<?php echo $pageNum; ?><?php echo $query_string; ?>"><?php echo $pageNum; ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Link to last page if it's not in the visible window -->
                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?page=<?php echo $total_pages; ?><?php echo $query_string; ?>"><?php echo $total_pages; ?></a>
                    </li>
                <?php endif; ?>

                <!-- Next Page Link -->
                <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?><?php echo $query_string; ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

    <?php

    if (empty($providers)) {
        echo '<div class="alert alert-info">Aucun fournisseur trouvé</div>';
    } else {
        foreach ($providers as $provider) {
            // Convert timestamps to readable dates.
            $customProvider = new customProviders();
            $provider = (object) $customProvider->timestampToDate((array) $provider);
            // Generate a unique modal ID for each provider.
            $modalId = 'deleteProviderModal_' . $provider->id;
            ?>
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
                                data-id="<?php echo $provider->id; ?>">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Provider Card -->
            <div class="provider__card card mb-3">
                <div class="card-body row">
                    <div class="dropdown custom-menu btn-group dropleft">
                        <button class="btn btn-secondary dropdown-toggle" type="button"
                            id="dropdownMenuButton_<?php echo $provider->id; ?>" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_<?php echo $provider->id; ?>">
                            <a class="dropdown-item"
                                href="/customdev/customproviders/provider_profile_page.php?email=<?php echo urlencode(displayValue($provider->email)); ?>&userid=<?php echo urlencode($provider->id); ?>">Voir
                                le profil</a>
                            <?php if ($actions['can_manage']) { ?>
                                <a class="dropdown-item"
                                    href="update_provider.php?email=<?php echo urlencode(displayValue($provider->email)); ?>">Modifier
                                    le profil</a>
                                <a class="dropdown-item"
                                    href="/customdev/customproviders/upload_documents.php?email=<?php echo urlencode(displayValue($provider->email)); ?>">Ajouter
                                    des pièces administratives</a>
                            <?php } ?>
                            <?php if ($actions['can_delete']) { ?>
                                <a class="dropdown-item deleteProviderBtn" href="#" data-toggle="modal"
                                    data-target="#<?php echo $modalId; ?>">Retirer le fournisseur</a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-md-3" id="left">
                        <h3>
                            <a
                                href="/customdev/customproviders/provider_profile_page.php?email=<?php echo urlencode(displayValue($provider->email)); ?>&userid=<?php echo urlencode($provider->id); ?>">
                                <?php echo displayValue($provider->firstname) . ' ' . displayValue($provider->lastname); ?>
                            </a>
                        </h3>
                        <div>
                            <span><b>Etat du dossier :</b></span><br>
                            <?php
                            $statut = 'Indisponible';
                            $bgClass = 'danger';
                            if ($provider->statut_dossier == 'Complet') {
                                $statut = 'Complet';
                                $bgClass = 'success';
                            } elseif ($provider->statut_dossier == 'Incomplet') {
                                $statut = 'Incomplet';
                                $bgClass = 'warning';
                            }
                            ?>
                            <div class="text-center p-2 bg-<?php echo $bgClass; ?>">
                                <?php echo $statut; ?>
                            </div>
                        </div>
                        <span><b>Validité des documents :</b>
                            <?php echo displayValue($provider->date_validite_document); ?></span>
                    </div>
                    <div class="col-md-6" id="center">
                        <h4>Profil</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <span><b>Fonction :</b></span> <?php echo displayValue($provider->fonction); ?><br>
                                <span><b>Organisme :</b></span> <?php echo (displayValue($provider->nom_etab, "nom_etab") . " " . displayValue($provider->raison)); ?><br>
                                <span><b>Localisation :</b></span>
                                <?php echo displayValue($provider->postalcode) . ' ' . displayValue($provider->city); ?><br>
                            </div>
                            <div class="col-md-6">
                                <span><b>Courriel :</b></span> <?php echo displayValue($provider->email); ?><br>
                                <span><b>Téléphone :</b></span> <?php echo displayValue($provider->telephone); ?><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" id="right">
                        <h4>Intervention</h4>
                        <div class="row">
                            <?php foreach ($arr as $key => $value) {
                                if (isset($provider->$key) && $provider->$key === 'Oui') { ?>
                                    <div class="badge bg-secondary m-1"><?php echo $value; ?></div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } // End foreach
        if ($actions['can_manage'] == true) { ?>
            <div class="provider__wrapper__footer">
                <a href="/customdev/customproviders/index.php" class="btn btn-primary">Ajouter un fournisseur</a>
            </div>
            <?php
        }
    } // End if-else for providers
    ?>

</div>

<?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Previous Page Link -->
            <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $current_page - 1; ?><?php echo $query_string; ?>"
                    tabindex="-1">Previous</a>
            </li>

            <!-- Link to first page if it's not in the visible window -->
            <?php if ($start > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=1<?php echo $query_string; ?>">1</a>
                </li>
                <?php if ($start > 2): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Page Number Links for the visible range -->
            <?php for ($pageNum = $start; $pageNum <= $end; $pageNum++): ?>
                <li class="page-item <?php echo ($pageNum == $current_page) ? 'active' : ''; ?>">
                    <a class="page-link"
                        href="?page=<?php echo $pageNum; ?><?php echo $query_string; ?>"><?php echo $pageNum; ?></a>
                </li>
            <?php endfor; ?>

            <!-- Link to last page if it's not in the visible window -->
            <?php if ($end < $total_pages): ?>
                <?php if ($end < $total_pages - 1): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link"
                        href="?page=<?php echo $total_pages; ?><?php echo $query_string; ?>"><?php echo $total_pages; ?></a>
                </li>
            <?php endif; ?>

            <!-- Next Page Link -->
            <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $current_page + 1; ?><?php echo $query_string; ?>">Next</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
</div>